<?php

namespace App\Services;

class EnhancedSentimentAnalyzer
{
    private $positiveWords;
    private $negativeWords;
    private $boosterWords;
    private $negationWords;

    public function __construct()
    {
        $this->initializeWordDictionaries();
    }

    /**
     * Analisis sentimen utama
     */
    public function analyzeSentiment($text)
    {
        if (empty($text)) {
            return [
                'sentiment' => 'neutral',
                'score' => 0,
                'confidence' => 0,
                'details' => []
            ];
        }

        // Preprocessing text
        $cleanText = $this->preprocessText($text);

        // Tokenize
        $words = $this->tokenize($cleanText);

        // Hitung skor sentimen
        $sentimentData = $this->calculateSentimentScore($words);

        return $sentimentData;
    }

    /**
     * Preprocessing text
     */
    private function preprocessText($text)
    {
        // Convert to lowercase
        $text = strtolower($text);

        // Remove URLs, mentions, hashtags
        $text = preg_replace('/http\S+|www\S+|https\S+/', '', $text);
        $text = preg_replace('/@\w+/', '', $text);
        $text = preg_replace('/#\w+/', '', $text);

        // Normalize repeated characters (bagusssss -> bagus)
        $text = preg_replace('/(.)\1{2,}/', '$1', $text);

        // Normalize common patterns
        $text = $this->normalizePatterns($text);

        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        return $text;
    }

    /**
     * Normalisasi pola-pola umum
     */
    private function normalizePatterns($text)
    {
        $patterns = [
            // Negasi
            '/\b(ga?k?|g\b|kga?|nga?k?|engga?|enggak|nggak)\b/' => 'tidak',
            '/\b(gada|gaada|ga ada)\b/' => 'tidak ada',
            '/\b(gabisa|gbs|ga bisa)\b/' => 'tidak bisa',
            '/\b(gpp|gapapa|ga papa)\b/' => 'tidak apa-apa',

            // Kata kerja
            '/\b(udh?|dah|udah)\b/' => 'sudah',
            '/\b(lg|lagi)\b/' => 'lagi',
            '/\b(liat|ngeliat)\b/' => 'lihat',
            '/\b(pke?|pake|pakai)\b/' => 'pakai',
            '/\b(bs|bisa)\b/' => 'bisa',

            // Kata sifat intensifier
            '/\b(bgt|banget|bgtt|bngtt|bngt)\b/' => 'sangat',
            '/\b(pol|poll|pooll)\b/' => 'sangat',
            '/\b(amat|amet)\b/' => 'sangat',

            // Kata ganti
            '/\b(gw|gue|ane|wa)\b/' => 'saya',
            '/\b(lu|elu|lo|elo)\b/' => 'kamu',
            '/\b(dia|dy)\b/' => 'dia',

            // Kata sambung/keterangan
            '/\b(yg|yang)\b/' => 'yang',
            '/\b(dgn|dngn|dengan)\b/' => 'dengan',
            '/\b(krn|karna|karena)\b/' => 'karena',
            '/\b(tp|tapi|tetapi)\b/' => 'tapi',
            '/\b(klo|kalo|kalau)\b/' => 'kalau',
            '/\b(skrg|skg|sekarang)\b/' => 'sekarang',
            '/\b(kmrn|kemarin)\b/' => 'kemarin',
            '/\b(ntr|nanti)\b/' => 'nanti',

            // Kata seru/partikel
            '/\b(dong|donk|dunk)\b/' => 'dong',
            '/\b(sih|si)\b/' => 'sih',
            '/\b(aja|ajah?|doang)\b/' => 'saja',
            '/\b(kok|koq)\b/' => 'kok',

            // Kata tanya
            '/\b(gmn|gmana|gimana|bagaimana)\b/' => 'bagaimana',
            '/\b(dmn|dimana|di mana)\b/' => 'dimana',
            '/\b(knp|kenapa|mengapa)\b/' => 'kenapa',
            '/\b(brp|berapa)\b/' => 'berapa',

            // Ungkapan terima kasih
            '/\b(thx|tks|mksh|makasih|tengkyu)\b/' => 'terima kasih',
            '/\b(ty|tysm)\b/' => 'terima kasih',

            // Kata waktu
            '/\b(hr|hari)\b/' => 'hari',
            '/\b(thn|tahun)\b/' => 'tahun',
            '/\b(bln|bulan)\b/' => 'bulan',
            '/\b(mgg|minggu)\b/' => 'minggu',

            // Kata tempat
            '/\b(rmh|rumah)\b/' => 'rumah',
            '/\b(jln|jalan)\b/' => 'jalan',
            '/\b(tmp|tempat)\b/' => 'tempat',

            // Kata kerja umum
            '/\b(mkan|makan)\b/' => 'makan',
            '/\b(mnm|minum)\b/' => 'minum',
            '/\b(tdr|tidur)\b/' => 'tidur',
            '/\b(krj|kerja)\b/' => 'kerja',
            '/\b(blj|belajar)\b/' => 'belajar',
        ];

        foreach ($patterns as $pattern => $replacement) {
            $text = preg_replace($pattern, $replacement, $text);
        }

        return $text;
    }

    /**
     * Tokenize text menjadi array kata
     */
    private function tokenize($text)
    {
        // Remove punctuation except for negation indicators
        $text = preg_replace('/[^\w\s]/', ' ', $text);

        // Split by whitespace
        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        return $words;
    }

    /**
     * Hitung skor sentimen dengan logic yang enhanced
     */
    private function calculateSentimentScore($words)
    {
        $positiveScore = 0;
        $negativeScore = 0;
        $totalWords = count($words);
        $details = [];

        for ($i = 0; $i < $totalWords; $i++) {
            $word = $words[$i];
            $score = 0;
            $multiplier = 1;

            // Check untuk negation words sebelum kata ini
            if ($i > 0 && in_array($words[$i-1], $this->negationWords)) {
                $multiplier = -1;
                $details[] = "Negation detected: '{$words[$i-1]}' before '{$word}'";
            }

            // Check untuk booster words sebelum kata ini
            if ($i > 0 && in_array($words[$i-1], $this->boosterWords)) {
                $multiplier = abs($multiplier) * 1.5; // Boost intensity
                $details[] = "Booster detected: '{$words[$i-1]}' before '{$word}'";
            }

            // Cek kata positif
            if (in_array($word, $this->positiveWords)) {
                $score = $this->getWordIntensity($word, 'positive') * $multiplier;
                $positiveScore += max(0, $score); // Only add if positive after multiplier
                $negativeScore += max(0, -$score); // Add to negative if became negative
                $details[] = "Positive word: '$word' (score: $score)";
            }
            // Cek kata negatif
            elseif (in_array($word, $this->negativeWords)) {
                $score = $this->getWordIntensity($word, 'negative') * $multiplier;
                $negativeScore += max(0, -$score); // Only add if negative after multiplier
                $positiveScore += max(0, $score); // Add to positive if became positive
                $details[] = "Negative word: '$word' (score: $score)";
            }
        }

        // Hitung skor akhir
        $finalScore = $positiveScore - $negativeScore;

        // Normalisasi berdasarkan panjang teks
        if ($totalWords > 0) {
            $finalScore = $finalScore / $totalWords;
        }

        // Tentukan sentimen berdasarkan threshold yang lebih nuanced
        $sentiment = $this->determineSentiment($finalScore);

        // Hitung confidence
        $confidence = $this->calculateConfidence($positiveScore, $negativeScore, $totalWords);

        return [
            'sentiment' => $sentiment,
            'score' => round($finalScore, 3),
            'confidence' => round($confidence, 2),
            'positive_score' => $positiveScore,
            'negative_score' => $negativeScore,
            'total_words' => $totalWords,
            'details' => $details
        ];
    }

    /**
     * Tentukan sentimen berdasarkan skor
     */
    private function determineSentiment($score)
    {
        if ($score >= 0.1) {
            return 'positive';
        } elseif ($score <= -0.1) {
            return 'negative';
        } else {
            return 'neutral';
        }
    }

    /**
     * Hitung confidence level
     */
    private function calculateConfidence($positiveScore, $negativeScore, $totalWords)
    {
        $totalScore = $positiveScore + $negativeScore;

        if ($totalWords == 0 || $totalScore == 0) {
            return 0;
        }

        // Confidence based on score intensity and text length
        $intensity = $totalScore / $totalWords;
        $confidence = min(100, $intensity * 50);

        return $confidence;
    }

    /**
     * Get word intensity (some words are stronger than others)
     */
    private function getWordIntensity($word, $type)
    {
        $strongWords = [
            'positive' => [
                'luar biasa' => 3,
                'sangat bagus' => 3,
                'excellent' => 3,
                'sempurna' => 3,
                'fantastis' => 3,
                'menakjubkan' => 3,
                'istimewa' => 2.5,
                'recommended' => 2.5,
                'terbaik' => 2.5,
                'memuaskan' => 2,
                'berkualitas' => 2,
                'profesional' => 2,
                'bagus' => 1.5,
                'baik' => 1.5,
                'senang' => 1.5,
                'puas' => 1.5
            ],
            'negative' => [
                'sangat buruk' => -3,
                'terrible' => -3,
                'mengecewakan' => -3,
                'payah' => -2.5,
                'buruk' => -2,
                'jelek' => -2,
                'lambat' => -1.5,
                'kurang' => -1.5,
                'tidak puas' => -2,
                'kecewa' => -2
            ]
        ];

        if (isset($strongWords[$type][$word])) {
            return $strongWords[$type][$word];
        }

        return $type === 'positive' ? 1 : -1;
    }

    /**
     * Initialize semua kamus kata
     */
    private function initializeWordDictionaries()
    {
        // Kata-kata positif (diperbanyak)
        $this->positiveWords = [
            // Kualitas Pelayanan
            'bagus', 'baik', 'excellent', 'sempurna', 'luar biasa', 'fantastis',
            'menakjubban', 'istimewa', 'hebat', 'keren', 'mantap', 'top',
            'maksimal', 'optimal', 'prima', 'berkualitas', 'premium',

            // Kepuasan
            'puas', 'senang', 'suka', 'cinta', 'love', 'happy', 'gembira',
            'bangga', 'terkesan', 'kagum', 'terpesona', 'terpukau',

            // Pelayanan
            'ramah', 'sopan', 'santun', 'baik hati', 'helpful', 'membantu',
            'responsif', 'tanggap', 'sigap', 'cepat', 'kilat', 'express',
            'profesional', 'kompeten', 'ahli', 'terampil', 'handal',

            // Kecepatan
            'cepat', 'kilat', 'express', 'instant', 'real time', 'langsung',
            'segera', 'on time', 'tepat waktu', 'punctual',

            // Kemudahan
            'mudah', 'simple', 'praktis', 'efisien', 'user friendly',
            'gampang', 'lancar', 'smooth', 'fleksibel',

            // Rekomendasi
            'recommended', 'recommend', 'suggest', 'terbaik', 'pilihan',
            'favorit', 'unggulan', 'andalan', 'juara',

            // Terima kasih
            'terima kasih', 'thanks', 'makasih', 'tengkyu', 'appreciate',
            'grateful', 'berterima kasih', 'menghargai',

            // Intensifier positif
            'sangat', 'amat', 'sekali', 'banget', 'poll', 'bener',
            'totally', 'absolutely', 'definitely', 'certainly',

            // Kualitas produk/layanan
            'fresh', 'segar', 'bersih', 'higienis', 'steril', 'aman',
            'nyaman', 'comfortable', 'cozy', 'hommy', 'asri',
            'lengkap', 'complete', 'komplit', 'variatif', 'beragam',

            // Kepercayaan
            'percaya', 'trust', 'yakin', 'confident', 'reliable',
            'terpercaya', 'kredibel', 'jujur', 'transparan',

            // Kelebihan
            'plus', 'bonus', 'lebih', 'extra', 'tambahan', 'gratis',
            'free', 'hemat', 'murah', 'affordable', 'worth it',

            // Emosi positif
            'wow', 'amazing', 'awesome', 'cool', 'nice', 'great',
            'wonderful', 'brilliant', 'superb', 'outstanding',
            'impressive', 'remarkable', 'extraordinary'
        ];

        // Kata-kata negatif (diperbanyak)
        $this->negativeWords = [
            // Kualitas buruk
            'buruk', 'jelek', 'bad', 'terrible', 'awful', 'horrible',
            'payah', 'parah', 'hancur', 'rusak', 'broken', 'damage',
            'cacat', 'error', 'salah', 'wrong', 'fail', 'gagal',

            // Ketidakpuasan
            'tidak puas', 'kecewa', 'disappointed', 'upset', 'frustrated',
            'kesal', 'marah', 'angry', 'mad', 'benci', 'hate',
            'disgusted', 'jijik', 'muak', 'bosan', 'boring',

            // Pelayanan buruk
            'kasar', 'rude', 'tidak sopan', 'arrogant', 'sombong',
            'cuek', 'acuh', 'ignore', 'diabaikan', 'tidak peduli',
            'tidak profesional', 'amatir', 'tidak kompeten',

            // Kecepatan/waktu
            'lambat', 'slow', 'lama', 'telat', 'terlambat', 'delay',
            'pending', 'loading', 'lag', 'lemot', 'stuck', 'hang',
            'macet', 'antri', 'queue', 'waiting', 'menunggu',

            // Kesulitan
            'sulit', 'difficult', 'hard', 'susah', 'ribet', 'complicated',
            'kompleks', 'rumit', 'membingungkan', 'confusing',
            'tidak jelas', 'unclear', 'ambigu',

            // Masalah teknis
            'error', 'bug', 'crash', 'down', 'offline', 'maintenance',
            'trouble', 'problem', 'issue', 'bermasalah', 'trouble',
            'not working', 'broken', 'rusak', 'mati',

            // Kekurangan
            'kurang', 'minus', 'lack', 'tidak ada', 'habis', 'kosong',
            'empty', 'sold out', 'unavailable', 'limited', 'terbatas',
            'sedikit', 'minim', 'insufficient', 'tidak cukup',

            // Kualitas rendah
            'murahan', 'cheap', 'norak', 'kampungan', 'kuno',
            'outdated', 'old fashioned', 'jadul', 'ketinggalan',
            'tidak modern', 'primitive',

            // Biaya
            'mahal', 'expensive', 'costly', 'overpriced', 'pricey',
            'tidak worth it', 'rugi', 'loss', 'boros', 'pemborosan',

            // Kebersihan/kondisi
            'kotor', 'dirty', 'bau', 'smell', 'stink', 'amis',
            'tidak higienis', 'unhygienic', 'jorok', 'dekil',
            'tidak terawat', 'lusuh', 'kumuh',

            // Intensifier negatif
            'sangat buruk', 'terrible sekali', 'parah banget',
            'totally bad', 'absolutely terrible', 'completely useless',

            // Penolakan
            'tidak', 'no', 'never', 'jangan', 'stop', 'cancel',
            'refuse', 'reject', 'decline', 'deny', 'menolak',

            // Kerugian
            'rugi', 'loss', 'damage', 'hancur', 'sia-sia', 'waste',
            'buang-buang', 'percuma', 'useless', 'pointless',

            // Emosi negatif
            'stress', 'depress', 'sad', 'sedih', 'galau', 'bingung',
            'pusing', 'dizzy', 'tired', 'capek', 'lelah', 'exhausted'
        ];

        // Kata-kata penguat (booster)
        $this->boosterWords = [
            'sangat', 'amat', 'sekali', 'banget', 'bgt', 'poll', 'pol',
            'bener', 'beneran', 'totally', 'absolutely', 'really',
            'very', 'extremely', 'super', 'ultra', 'mega', 'giga',
            'paling', 'ter', 'most', 'completely', 'entirely',
            'perfectly', 'fully', 'totally', 'quite', 'rather',
            'cukup', 'lumayan', 'agak', 'sedikit', 'somewhat'
        ];

        // Kata-kata negasi
        $this->negationWords = [
            'tidak', 'tak', 'nggak', 'ngga', 'enggak', 'ga', 'gak',
            'no', 'never', 'neither', 'nor', 'nothing', 'nowhere',
            'nobody', 'none', 'not', 'jangan', 'stop', 'bukan',
            'without', 'lacking', 'minus', 'except', 'but',
            'however', 'although', 'though', 'unless', 'instead'
        ];
    }
}

// Helper trait untuk controller
trait SentimentAnalysisTrait
{
    protected $sentimentAnalyzer;

    protected function initializeSentimentAnalyzer()
    {
        if (!$this->sentimentAnalyzer) {
            $this->sentimentAnalyzer = new EnhancedSentimentAnalyzer();
        }
    }

    protected function analyzeSentiment($text)
    {
        $this->initializeSentimentAnalyzer();
        return $this->sentimentAnalyzer->analyzeSentiment($text);
    }

    protected function getSentimentLabel($text)
    {
        $result = $this->analyzeSentiment($text);
        return $result['sentiment'];
    }

    protected function getSentimentScore($text)
    {
        $result = $this->analyzeSentiment($text);
        return $result['score'];
    }
}

// Usage example in DataSkmController:
/*
class DataSkmController extends Controller
{
    use SentimentAnalysisTrait;

    private function analyzeSentiment($text)
    {
        $analyzer = new EnhancedSentimentAnalyzer();
        $result = $analyzer->analyzeSentiment($text);
        return $result['sentiment'];
    }
}
*/
