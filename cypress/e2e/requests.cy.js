
const baseUrl = 'http://localhost/559-Capstone-Mohawk-capstone-LocalConnect';

// Existing test accounts
const user2 = {
    email: 'user2@testing.com',
    password: 'Username@12'
};

const user3 = {
    email: 'User3@example.com',
    password: 'Testing@12'
};

// Reusable login helper
const login = (user) => {
    cy.clearCookies();
    cy.visit(`${baseUrl}/login.php`);

    cy.get('input[name="email"]').type(user.email);
    cy.get('input[name="password"]').type(user.password);

    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/dashboard.php');
};

// User2 creates a post to receive collaboration requests
const createPostAsUser2 = (title) => {
    login(user2);

    cy.visit(`${baseUrl}/post-create.php`);

    cy.get('input[name="title"]').type(title);
    cy.get('textarea[name="description"]')
        .type('Cypress automated collaboration request test.');
    cy.get('textarea[name="required_skills"]')
        .type('PHP, JavaScript');
    cy.get('input[name="location"]').type('Hamilton');

    cy.contains('button', 'Create Collaboration').click();

    cy.url().should('include', '/posts.php');
    cy.contains('h3.post-title', title).should('be.visible');
};

// User3 sends a request to User2's post
const sendRequestAsUser3 = (title) => {
    login(user3);

    cy.visit(`${baseUrl}/posts.php`);

    cy.contains('h3.post-title', title)
        .should('be.visible')
        .closest('.post-card')
        .within(() => {
            cy.get('textarea[name="message"]')
                .type('I would like to collaborate on this project.');

            cy.contains('button', 'Send Request').click();
        });

    cy.url().should('include', '/posts.php');

    cy.contains('h3.post-title', title)
        .closest('.post-card')
        .should('contain.text', 'Pending');
};

// Verify the sender sees the updated request status
const verifySenderStatus = (title, status) => {
    login(user3);

    cy.visit(`${baseUrl}/requests.php`);

    cy.contains('h2', 'Sent Requests')
        .parent()
        .contains('.request-card', title)
        .within(() => {
            cy.contains('strong', 'Status:')
                .parent()
                .should('contain.text', status);
        });
};

describe('LocalConnect Collaboration Requests - F4', () => {

    it('TC-REQ1 - User can send a collaboration request', () => {
        const title = `Cypress Send Request ${Date.now()}`;

        // User2 creates a collaboration post
        createPostAsUser2(title);

        // User3 sends a request
        sendRequestAsUser3(title);

        // Verify the request appears in Sent Requests
        cy.visit(`${baseUrl}/requests.php`);

        cy.contains('h2', 'Sent Requests')
            .parent()
            .contains('.request-card', title)
            .should('be.visible')
            .and('contain.text', 'pending');

        // User2 receives the request
        login(user2);
        cy.visit(`${baseUrl}/requests.php`);

        cy.contains('h2', 'Received Requests')
            .parent()
            .contains('.request-card', title)
            .should('be.visible')
            .and('contain.text', 'pending');
    });

    it('TC-REQ2 - Post owner can accept a collaboration request', () => {
        const title = `Cypress Accept Request ${Date.now()}`;

        createPostAsUser2(title);
        sendRequestAsUser3(title);

        // User2 accepts the request
        login(user2);
        cy.visit(`${baseUrl}/requests.php`);

        cy.contains('h2', 'Received Requests')
            .parent()
            .contains('.request-card', title)
            .within(() => {
                cy.contains('button', 'Accept').click();
            });

        cy.url().should('include', '/requests.php');

        // Verify receiver sees accepted status
        cy.contains('h2', 'Received Requests')
            .parent()
            .contains('.request-card', title)
            .should('contain.text', 'accepted');

        // Verify sender also sees accepted status
        verifySenderStatus(title, 'accepted');
    });

    it('TC-REQ3 - Post owner can decline a collaboration request', () => {
        const title = `Cypress Decline Request ${Date.now()}`;

        createPostAsUser2(title);
        sendRequestAsUser3(title);

        // User2 declines the request
        login(user2);
        cy.visit(`${baseUrl}/requests.php`);

        cy.contains('h2', 'Received Requests')
            .parent()
            .contains('.request-card', title)
            .within(() => {
                cy.contains('button', 'Decline').click();
            });

        cy.url().should('include', '/requests.php');

        // Verify receiver sees declined status
        cy.contains('h2', 'Received Requests')
            .parent()
            .contains('.request-card', title)
            .should('contain.text', 'declined');

        // Verify sender also sees declined status
        verifySenderStatus(title, 'declined');
    });

});
