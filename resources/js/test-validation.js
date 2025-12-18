/**
 * Test Validation Script for PPKS Dinsos Application
 * This script can be used to test validation functionality
 */

// Test function to validate NIK format
function testNIKValidation() {
    console.log('Testing NIK Validation...');

    const testCases = [
        { input: '1234567890123456', expected: true, description: 'Valid 16-digit NIK' },
        { input: '123456789012345', expected: false, description: 'Invalid 15-digit NIK' },
        { input: '12345678901234567', expected: false, description: 'Invalid 17-digit NIK' },
        { input: '123456789012345a', expected: false, description: 'Invalid NIK with letter' },
        { input: '', expected: false, description: 'Empty NIK' },
    ];

    testCases.forEach(testCase => {
        const validator = new FormValidator('test-form');
        const result = validator.validateNIK(testCase.input);
        const passed = (result === null) === testCase.expected;

        console.log(`${testCase.description}: ${passed ? '✓ PASS' : '✗ FAIL'}`);
        if (!passed) {
            console.log(`  Expected: ${testCase.expected}, Got: ${result === null}`);
        }
    });
}

// Test function to validate Indonesian names
function testNameValidation() {
    console.log('Testing Name Validation...');

    const testCases = [
        { input: 'John Doe', expected: true, description: 'Valid name with space' },
        { input: 'Maria Santos', expected: true, description: 'Valid Indonesian name' },
        { input: 'Ahmad Rizki', expected: true, description: 'Valid Indonesian name' },
        { input: 'John123', expected: false, description: 'Invalid name with numbers' },
        { input: 'John@Doe', expected: false, description: 'Invalid name with special character' },
        { input: '', expected: false, description: 'Empty name' },
        { input: 'O\'Connor', expected: true, description: 'Valid name with apostrophe' },
        { input: 'Mary-Jane', expected: true, description: 'Valid name with hyphen' },
    ];

    testCases.forEach(testCase => {
        const validator = new FormValidator('test-form');
        const result = validator.validateNama(testCase.input);
        const passed = (result === null) === testCase.expected;

        console.log(`${testCase.description}: ${passed ? '✓ PASS' : '✗ FAIL'}`);
        if (!passed) {
            console.log(`  Expected: ${testCase.expected}, Got: ${result === null}`);
        }
    });
}

// Test function to validate addresses
function testAddressValidation() {
    console.log('Testing Address Validation...');

    const testCases = [
        { input: 'Jl. Merdeka No. 123', expected: true, description: 'Valid address' },
        { input: 'Jl. Sudirman No. 45, RT 01/RW 02', expected: true, description: 'Valid Indonesian address' },
        { input: 'Jl. Soekarno-Hatta', expected: true, description: 'Valid address with hyphen' },
        { input: '', expected: false, description: 'Empty address' },
        { input: 'Addr', expected: false, description: 'Too short address' },
        { input: 'Jl. Test@Address', expected: false, description: 'Invalid address with special character' },
    ];

    testCases.forEach(testCase => {
        const validator = new FormValidator('test-form');
        const result = validator.validateAlamat(testCase.input);
        const passed = (result === null) === testCase.expected;

        console.log(`${testCase.description}: ${passed ? '✓ PASS' : '✗ FAIL'}`);
        if (!passed) {
            console.log(`  Expected: ${testCase.expected}, Got: ${result === null}`);
        }
    });
}

// Test function to validate Indonesian text
function testIndonesianTextValidation() {
    console.log('Testing Indonesian Text Validation...');

    const testCases = [
        { input: 'Program Bantuan Sosial', expected: true, description: 'Valid Indonesian text' },
        { input: 'Bantuan Langsung Tunai', expected: true, description: 'Valid Indonesian text with spaces' },
        { input: 'PKH & BST', expected: true, description: 'Valid text with ampersand' },
        { input: 'Bantuan untuk masyarakat', expected: true, description: 'Valid Indonesian text' },
        { input: '', expected: true, description: 'Empty text (optional field)' },
        { input: 'Test<script>alert("xss")</script>', expected: false, description: 'XSS attempt' },
    ];

    testCases.forEach(testCase => {
        const validator = new FormValidator('test-form');
        const result = validator.validateIndonesianText(testCase.input);
        const passed = (result === null) === testCase.expected;

        console.log(`${testCase.description}: ${passed ? '✓ PASS' : '✗ FAIL'}`);
        if (!passed) {
            console.log(`  Expected: ${testCase.expected}, Got: ${result === null}`);
        }
    });
}

// Run all tests
function runAllTests() {
    console.log('=== PPKS Dinsos Validation Tests ===');
    console.log('');

    testNIKValidation();
    console.log('');

    testNameValidation();
    console.log('');

    testAddressValidation();
    console.log('');

    testIndonesianTextValidation();
    console.log('');

    console.log('=== Test Complete ===');
}

// Auto-run tests when page loads
document.addEventListener('DOMContentLoaded', function () {
    // Check if we're on a test page
    if (window.location.search.includes('test=true')) {
        runAllTests();
    }
});

// Make test functions available globally
window.testValidation = {
    runAllTests,
    testNIKValidation,
    testNameValidation,
    testAddressValidation,
    testIndonesianTextValidation
};