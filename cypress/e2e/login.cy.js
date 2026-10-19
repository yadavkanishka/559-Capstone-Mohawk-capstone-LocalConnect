describe('LocalConnect Login', () => {

    it('TC-F1 - Valid login loads the dashboard', () => {
        cy.visit('http://localhost/559-Capstone-Mohawk-capstone-LocalConnect/login.php');

        cy.get('input[name="email"]').type('user3@example.com');
        cy.get('input[name="password"]').type('Testing@12');

        cy.get('button[type="submit"]').click();

        cy.url().should('include', '/dashboard.php');
         cy.get('.dashboard-welcome h1').should('be.visible');
    cy.contains('Your next collaboration starts here.').should('be.visible');

    // Verify the dashboard's main navigation actions.
    cy.contains('a', 'Create a Post').should('be.visible');
    cy.contains('a', 'Browse Collaborations').should('be.visible');
    cy.contains('a', 'My Profile').should('be.visible');
    });

    it('TC-F2 - Invalid login displays an error message', () => {
        cy.visit('http://localhost/559-Capstone-Mohawk-capstone-LocalConnect/login.php');

        cy.get('input[name="email"]').type('wrong@example.com');
        cy.get('input[name="password"]').type('WrongPassword1!');

        cy.get('button[type="submit"]').click();

        cy.contains('Invalid email or password.').should('be.visible');
    });

});