const { execFileSync } = require('child_process');
const path = require('path');

const php = 'C:\\xampp\\php\\php.exe';
const runner = path.join(__dirname, 'profile_validation_runner.php');

function validateProfile(fullName, location, skills, interests, biography) {
    const input = JSON.stringify({
        full_name: fullName,
        location: location,
        skills: skills,
        interests: interests,
        biography: biography
    });

    const output = execFileSync(php, [runner, input], {
        encoding: 'utf8'
    });

    return JSON.parse(output);
}

describe('Profile Validation', () => {

    test('accepts a valid profile', () => {
        const errors = validateProfile(
            'Kanishka Yadav',
            'Hamilton, ON',
            'PHP, JavaScript, SQL',
            'Technology, volunteering',
            'I enjoy working on community projects.'
        );

        expect(errors).toEqual([]);
    });

    test('rejects an empty full name', () => {
        const errors = validateProfile(
            '',
            'Hamilton, ON',
            'PHP',
            'Technology',
            'Hello'
        );

        expect(errors).toContain('Full name is required.');
    });

    test('rejects a full name longer than 150 characters', () => {
        const errors = validateProfile(
            'A'.repeat(151),
            'Hamilton, ON',
            'PHP',
            'Technology',
            'Hello'
        );

        expect(errors).toContain(
            'Full name must be 150 characters or less.'
        );
    });

    test('rejects a location longer than 100 characters', () => {
        const errors = validateProfile(
            'Kanishka Yadav',
            'A'.repeat(101),
            'PHP',
            'Technology',
            'Hello'
        );

        expect(errors).toContain(
            'Location must be 100 characters or less.'
        );
    });

    test('rejects skills longer than 2000 characters', () => {
        const errors = validateProfile(
            'Kanishka Yadav',
            'Hamilton, ON',
            'A'.repeat(2001),
            'Technology',
            'Hello'
        );

        expect(errors).toContain(
            'Skills must be 2000 characters or less.'
        );
    });

    test('rejects interests longer than 2000 characters', () => {
        const errors = validateProfile(
            'Kanishka Yadav',
            'Hamilton, ON',
            'PHP',
            'A'.repeat(2001),
            'Hello'
        );

        expect(errors).toContain(
            'Interests must be 2000 characters or less.'
        );
    });

    test('rejects biography longer than 5000 characters', () => {
        const errors = validateProfile(
            'Kanishka Yadav',
            'Hamilton, ON',
            'PHP',
            'Technology',
            'A'.repeat(5001)
        );

        expect(errors).toContain(
            'Biography must be 5000 characters or less.'
        );
    });

});