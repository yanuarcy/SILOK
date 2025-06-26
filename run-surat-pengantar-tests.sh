#!/bin/bash

# run-surat-pengantar-tests.sh
# Script untuk menjalankan white box testing SuratPengantar dengan summary lengkap

echo "🧪 SuratPengantar White Box Testing Suite"
echo "=========================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Initialize counters
TOTAL_TEST_FILES=0
PASSED_TEST_FILES=0
FAILED_TEST_FILES=0
TOTAL_TESTS=0
TOTAL_PASSED=0
TOTAL_FAILED=0
TOTAL_INCOMPLETE=0
TOTAL_WARNINGS=0

# Array to store test results
declare -a TEST_RESULTS

# Function to run a test file and show full output
run_test_file() {
    local test_file=$1
    local test_name=$2

    echo -e "${BLUE}🚀 Running ${test_name}...${NC}"

    # Run test and show full output (like original)
    php artisan test $test_file --env=testing
    local exit_code=$?

    # Capture output again to parse statistics
    local output=$(php artisan test $test_file --env=testing 2>&1)

    # Parse the output for test statistics
    local passed=$(echo "$output" | grep -o '[0-9]\+ passed' | grep -o '[0-9]\+' | head -1 || echo "0")
    local failed=$(echo "$output" | grep -o '[0-9]\+ failed' | grep -o '[0-9]\+' | head -1 || echo "0")
    local incomplete=$(echo "$output" | grep -o '[0-9]\+ incomplete' | grep -o '[0-9]\+' | head -1 || echo "0")
    local warnings=$(echo "$output" | grep -o '[0-9]\+ warnings\?' | grep -o '[0-9]\+' | head -1 || echo "0")

    # If no specific numbers found, try to extract from "Tests:" line
    if [[ $passed == "0" && $failed == "0" && $incomplete == "0" ]]; then
        local tests_line=$(echo "$output" | grep "Tests:" | tail -1)
        if [[ -n "$tests_line" ]]; then
            passed=$(echo "$tests_line" | grep -o '[0-9]\+ passed' | grep -o '[0-9]\+' || echo "0")
            failed=$(echo "$tests_line" | grep -o '[0-9]\+ failed' | grep -o '[0-9]\+' || echo "0")
            incomplete=$(echo "$tests_line" | grep -o '[0-9]\+ incomplete' | grep -o '[0-9]\+' || echo "0")
        fi
    fi

    # Ensure we have valid numbers
    [[ -z "$passed" ]] && passed=0
    [[ -z "$failed" ]] && failed=0
    [[ -z "$incomplete" ]] && incomplete=0
    [[ -z "$warnings" ]] && warnings=0

    # Update counters
    TOTAL_TEST_FILES=$((TOTAL_TEST_FILES + 1))
    TOTAL_PASSED=$((TOTAL_PASSED + passed))
    TOTAL_FAILED=$((TOTAL_FAILED + failed))
    TOTAL_INCOMPLETE=$((TOTAL_INCOMPLETE + incomplete))
    TOTAL_WARNINGS=$((TOTAL_WARNINGS + warnings))
    TOTAL_TESTS=$((TOTAL_TESTS + passed + failed + incomplete))

    # Determine file status
    local file_status="PASSED"
    local status_color=$GREEN
    if [[ $exit_code -ne 0 || $failed -gt 0 ]]; then
        file_status="FAILED"
        status_color=$RED
        FAILED_TEST_FILES=$((FAILED_TEST_FILES + 1))
    else
        PASSED_TEST_FILES=$((PASSED_TEST_FILES + 1))
    fi

    # Store result for summary (like image 1 style)
    local result_line="${status_color}${file_status}${NC} - $passed passed, $failed failed, $incomplete incomplete, $warnings warnings"
    TEST_RESULTS+=("$test_name: $result_line")

    echo ""
}

# Check if .env.testing exists
if [ ! -f .env.testing ]; then
    echo -e "${YELLOW}⚠️  .env.testing not found. Creating from .env...${NC}"
    cp .env .env.testing
    sed -i 's/DB_DATABASE=.*/DB_DATABASE=silok_testing/' .env.testing
    echo -e "${GREEN}✅ .env.testing created${NC}"
    echo ""
fi

# Clear caches
echo -e "${BLUE}🧹 Clearing caches...${NC}"
php artisan config:clear --env=testing >/dev/null 2>&1
php artisan route:clear --env=testing >/dev/null 2>&1
echo ""

# Setup testing database
echo -e "${BLUE}🔧 Setting up testing database...${NC}"
echo -e "${YELLOW}   Recreating database...${NC}"
mysql -u root -e "DROP DATABASE IF EXISTS silok_testing; CREATE DATABASE silok_testing;" 2>/dev/null || {
    echo -e "${YELLOW}   Note: Database recreation may require manual setup${NC}"
}

echo -e "${BLUE}   Running fresh migrations...${NC}"
php artisan migrate:fresh --env=testing --force >/dev/null 2>&1
echo ""

# Record start time
START_TIME=$(date +%s)

echo -e "${PURPLE}📊 Running All SuratPengantar Tests...${NC}"
echo -e "${CYAN}===========================================================${NC}"
echo ""

# Run each test file with full output (like image 2)
run_test_file "tests/Feature/SuratPengantar/SuratPengantarCrudTest.php" "CRUD Tests"
run_test_file "tests/Feature/SuratPengantar/SuratPengantarApprovalTest.php" "Approval Tests"
run_test_file "tests/Feature/SuratPengantar/SuratPengantarValidationTest.php" "Validation Tests"
run_test_file "tests/Feature/SuratPengantar/SuratPengantarSecurityTest.php" "Security Tests"
run_test_file "tests/Feature/SuratPengantar/SuratPengantarPerformanceTest.php" "Performance Tests"
run_test_file "tests/Feature/SuratPengantar/SuratPengantarIntegrationTest.php" "Integration Tests"

# Calculate total duration
END_TIME=$(date +%s)
REAL_DURATION=$((END_TIME - START_TIME))

# Display summary (like image 1 style)
echo -e "${CYAN}===========================================================${NC}"
echo -e "${PURPLE}📈 COMPREHENSIVE TEST SUMMARY${NC}"
echo -e "${CYAN}===========================================================${NC}"
echo ""

# Show each test file result summary
for result in "${TEST_RESULTS[@]}"; do
    echo -e "${BLUE}🚀 $result${NC}"
done

echo ""
echo -e "${CYAN}===========================================================${NC}"

# Overall statistics
echo -e "${BLUE}📊 Overall Statistics:${NC}"
echo ""

# Test Files Summary
echo -e "${GREEN}✅ Test Files Passed:           ${PASSED_TEST_FILES}/${TOTAL_TEST_FILES}${NC}"
if [[ $FAILED_TEST_FILES -gt 0 ]]; then
    echo -e "${RED}❌ Test Files Failed:           ${FAILED_TEST_FILES}/${TOTAL_TEST_FILES}${NC}"
fi

echo ""

# Individual Tests Summary
echo -e "${GREEN}✅ Individual Tests Passed:     ${TOTAL_PASSED}${NC}"
if [[ $TOTAL_FAILED -gt 0 ]]; then
    echo -e "${RED}❌ Individual Tests Failed:     ${TOTAL_FAILED}${NC}"
fi
if [[ $TOTAL_INCOMPLETE -gt 0 ]]; then
    echo -e "${YELLOW}⏸️  Individual Tests Incomplete: ${TOTAL_INCOMPLETE}${NC}"
fi
if [[ $TOTAL_WARNINGS -gt 0 ]]; then
    echo -e "${YELLOW}⚠️  Total Warnings:             ${TOTAL_WARNINGS}${NC}"
fi

echo -e "${BLUE}📝 Total Individual Tests:      ${TOTAL_TESTS}${NC}"

echo ""

# Performance Summary
echo -e "${BLUE}⏱️  Total Execution Time:        ${REAL_DURATION} seconds${NC}"

echo ""

# Success Rate (simple calculation)
if [[ $TOTAL_TESTS -gt 0 ]]; then
    SUCCESS_RATE=$((TOTAL_PASSED * 100 / TOTAL_TESTS))
    echo -e "${BLUE}📈 Success Rate:                ${SUCCESS_RATE}% (${TOTAL_PASSED}/${TOTAL_TESTS} tests)${NC}"
fi

echo ""

# Final Status
if [[ $FAILED_TEST_FILES -eq 0 && $TOTAL_FAILED -eq 0 ]]; then
    echo -e "${GREEN}🎉 ALL TESTS PASSED SUCCESSFULLY! 🎉${NC}"
    echo -e "${GREEN}✨ Your SuratPengantar module is working perfectly!${NC}"
    echo ""
    echo -e "${CYAN}📊 Perfect Score: ${TOTAL_PASSED}/${TOTAL_TESTS} tests passed${NC}"
    if [[ $TOTAL_INCOMPLETE -gt 0 ]]; then
        echo -e "${YELLOW}ℹ️  Note: ${TOTAL_INCOMPLETE} tests were marked as incomplete (expected behavior)${NC}"
    fi
else
    echo -e "${RED}❌ SOME TESTS FAILED${NC}"
    echo -e "${YELLOW}💡 Common issues to check:${NC}"
    echo "   - Database field requirements (nik, rt, rw fields)"
    echo "   - Route definitions and controller methods"
    echo "   - Required fields in test data"
    echo "   - Permission and authorization logic"
    echo ""
    echo -e "${BLUE}📊 Results: ${TOTAL_PASSED} passed, ${TOTAL_FAILED} failed, ${TOTAL_INCOMPLETE} incomplete${NC}"
fi

echo ""
echo -e "${CYAN}===========================================================${NC}"
echo -e "${BLUE}📈 For detailed coverage report, run:${NC}"
echo "    ./vendor/bin/phpunit tests/Feature/SuratPengantar/ --coverage-html coverage-report"
echo -e "${CYAN}===========================================================${NC}"
