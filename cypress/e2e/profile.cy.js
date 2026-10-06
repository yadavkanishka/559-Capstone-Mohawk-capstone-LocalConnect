describe('LocalConnect Profile', () => {

    beforeEach(() => {
        cy.visit('http://localhost/559-Capstone-Mohawk-capstone-LocalConnect/login.php');

        cy.get('input[name="email"]').type('User3@example.com');
        cy.get('input[name="password"]').type('Testing@12');

        cy.get('button[type="submit"]').click();

        cy.url().should('include', '/dashboard.php');
    });

    it('TC-P1 - User can open their profile', () => {
        cy.visit('http://localhost/559-Capstone-Mohawk-capstone-LocalConnect/profile.php');

        cy.contains('My Profile').should('be.visible');

        // User clicks Edit Profile before interacting with the fields
        cy.contains('button', 'Edit Profile').should('be.visible').click();

        cy.get('input[name="full_name"]').should('be.visible');
        cy.get('input[name="location"]').should('be.visible');
        cy.get('textarea[name="skills"]').should('be.visible');
        cy.get('textarea[name="interests"]').should('be.visible');
        cy.get('textarea[name="biography"]').should('be.visible');
        cy.contains('button', 'Save Profile').should('be.visible');
    });

    it('TC-P2 - User can update and save their profile', () => {
        cy.visit('http://localhost/559-Capstone-Mohawk-capstone-LocalConnect/profile.php');

        // Open the edit form
        cy.contains('button', 'Edit Profile').should('be.visible').click();

        cy.get('input[name="full_name"]')
            .clear()
            .type('Cypress Test User');

        cy.get('input[name="location"]')
            .clear()
            .type('Hamilton, ON');

        cy.get('textarea[name="skills"]')
            .clear()
            .type('PHP, JavaScript, SQL');

        cy.get('textarea[name="interests"]')
            .clear()
            .type('Technology, Community Projects');

        cy.get('textarea[name="biography"]')
            .clear()
            .type('This profile was updated using Cypress.');

        cy.contains('button', 'Save Profile').click();

        cy.contains('Profile updated successfully.')
            .should('be.visible');

        // After saving, the page returns to the profile view
        cy.contains('Cypress Test User').should('be.visible');
        cy.contains('Hamilton, ON').should('be.visible');
        cy.contains('PHP, JavaScript, SQL').should('be.visible');
        cy.contains('Technology, Community Projects').should('be.visible');
        cy.contains('This profile was updated using Cypress.').should('be.visible');
    });

    it('TC-P3 - Saved profile information remains after reload', () => {
        cy.visit('http://localhost/559-Capstone-Mohawk-capstone-LocalConnect/profile.php');

        cy.contains('Cypress Test User').should('be.visible');
        cy.contains('Hamilton, ON').should('be.visible');
        cy.contains('PHP, JavaScript, SQL').should('be.visible');
        cy.contains('Technology, Community Projects').should('be.visible');
        cy.contains('This profile was updated using Cypress.').should('be.visible');
    });

});