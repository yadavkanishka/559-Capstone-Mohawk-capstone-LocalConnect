describe('LocalConnect Login', () => {

    it('TC-F1 - Valid login loads the dashboard', () => {
        cy.visit('http://localhost/559-Capstone-Mohawk-capstone-LocalConnect/login.php');

        cy.get('input[name="email"]').type('user2@testing.com');
        cy.get('input[name="password"]').type('Username@12');

        cy.get('button[type="submit"]').click();

        cy.url().should('include', '/dashboard.php');
        cy.contains('LocalConnect Dashboard').should('be.visible');
        cy.contains('You are successfully logged in.').should('be.visible');
    });

    it('TC-F2 - Invalid login displays an error message', () => {
        cy.visit('http://localhost/559-Capstone-Mohawk-capstone-LocalConnect/login.php');

        cy.get('input[name="email"]').type('wrong@example.com');
        cy.get('input[name="password"]').type('WrongPassword1!');

        cy.get('button[type="submit"]').click();

        cy.contains('Invalid email or password.').should('be.visible');
    });

});