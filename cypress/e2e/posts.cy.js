
const baseUrl = 'http://localhost/559-Capstone-Mohawk-capstone-LocalConnect';

describe('LocalConnect Collaboration Posts', () => {
    beforeEach(() => {
        cy.visit(`${baseUrl}/login.php`);

        cy.get('input[name="email"]').type('User3@example.com');
        cy.get('input[name="password"]').type('Testing@12');

        cy.get('button[type="submit"]').click();
        cy.url().should('include', '/dashboard.php');
    });

    it('TC-POST1 - User can open the create collaboration form', () => {
        cy.visit(`${baseUrl}/post-create.php`);

        cy.contains('h1', 'Create a Collaboration').should('be.visible');
        cy.get('input[name="title"]').should('be.visible');
        cy.get('textarea[name="description"]').should('be.visible');
        cy.get('textarea[name="required_skills"]').should('be.visible');
        cy.get('input[name="location"]').should('be.visible');

        cy.contains('button', 'Create Collaboration').should('be.visible');
    });

    it('TC-POST2 - Empty required fields are rejected by the form', () => {
        cy.visit(`${baseUrl}/post-create.php`);

        cy.get('input[name="title"]').should('have.attr', 'required');
        cy.get('textarea[name="description"]').should('have.attr', 'required');
    });
});
