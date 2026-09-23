const { execFileSync } = require('child_process');
const path = require('path');

const php = 'C:\\xampp\\php\\php.exe';
const runner = path.join(__dirname, 'password_validation_runner.php');

function validatePassword(password) {
    const result = execFileSync(
        php,
        [runner, password],
        { encoding: 'utf8' }
    ).trim();

    return result === 'true';
}

test('accepts a valid password', () => {
    expect(validatePassword('P@ssword1')).toBe(true);
});

test('rejects a password without required complexity', () => {
    expect(validatePassword('password')).toBe(false);
});

test('rejects a password shorter than 8 characters', () => {
    expect(validatePassword('P@ss1')).toBe(false);
});

test('rejects a password without an uppercase letter', () => {
    expect(validatePassword('p@ssword1')).toBe(false);
});

test('rejects a password without a lowercase letter', () => {
    expect(validatePassword('P@SSWORD1')).toBe(false);
});

test('rejects a password without a number', () => {
    expect(validatePassword('P@ssword')).toBe(false);
});

test('rejects a password without a special character', () => {
    expect(validatePassword('Password1')).toBe(false);
});