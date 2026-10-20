
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
    
    it('TC-POST3 - User can create, edit, and delete their own collaboration', () => {
        const originalTitle = `Cypress Lifecycle ${Date.now()}`;
        const editedTitle = `${originalTitle} - Edited`;

        // Create a collaboration
        cy.visit(`${baseUrl}/post-create.php`);

        cy.get('input[name="title"]').type(originalTitle);
        cy.get('textarea[name="description"]')
            .type('Automated test collaboration description.');
        cy.get('textarea[name="required_skills"]')
            .type('PHP, JavaScript');
        cy.get('input[name="location"]').type('Hamilton');

        cy.contains('button', 'Create Collaboration').click();

        // Confirm the new collaboration appears
        cy.url().should('include', '/posts.php');

        cy.contains('h3.post-title', originalTitle)
            .should('be.visible')
            .closest('.post-card')
            .as('createdPost');

        // Edit the collaboration
        cy.get('@createdPost').contains('a', 'Edit').click();

        cy.url().should('include', '/post-edit.php');
        cy.contains('h1', 'Edit Collaboration Post').should('be.visible');

        cy.get('input[name="title"]')
            .clear()
            .type(editedTitle);

        cy.contains('button', 'Save Changes').click();

        // Confirm the edited title is displayed
        cy.url().should('include', '/posts.php');

        cy.contains('h3.post-title', editedTitle)
            .should('be.visible')
            .closest('.post-card')
            .as('editedPost');

        // Delete the collaboration created by this test
        cy.get('@editedPost').contains('button', 'Delete').click();

        // Confirm the collaboration is gone
        cy.contains('h3.post-title', editedTitle).should('not.exist');
    });
    
    it('TC-POST4 - User cannot edit another user\'s collaboration', () => {
        const title = `Cypress Unauthorized Edit ${Date.now()}`;

        // User3 creates the post.
        cy.visit(`${baseUrl}/post-create.php`);
        cy.get('input[name="title"]').type(title);
        cy.get('textarea[name="description"]').type('Authorization test post.');
        cy.get('textarea[name="required_skills"]').type('PHP');
        cy.get('input[name="location"]').type('Hamilton');
        cy.contains('button', 'Create Collaboration').click();

        cy.url().should('include', '/posts.php');

        // Capture the post ID from its Edit link.
        cy.contains('h3.post-title', title)
            .closest('.post-card')
            .find('a.edit-btn')
            .invoke('attr', 'href')
            .then((href) => {
                const postId = new URL(href, window.location.origin)
                    .searchParams.get('id');

                expect(postId).to.exist;

                // Switch to User2's session.
                cy.clearCookies();
                cy.visit(`${baseUrl}/login.php`);
                cy.get('input[name="email"]').type('user2@testing.com');
                cy.get('input[name="password"]').type('Username@12');
                cy.get('button[type="submit"]').click();
                cy.url().should('include', '/dashboard.php');

                // Attempt to edit User3's post.
                cy.visit(`${baseUrl}/post-edit.php?id=${postId}`);

                // The application should redirect unauthorized users.
                cy.url().should('include', '/posts.php');

                // Confirm the original post still exists.
                cy.contains('h3.post-title', title).should('be.visible');
            });
    });

    it('TC-POST5 - User cannot delete another user\'s collaboration', () => {
        const title = `Cypress Unauthorized Delete ${Date.now()}`;

        // User3 creates the post.
        cy.visit(`${baseUrl}/post-create.php`);
        cy.get('input[name="title"]').type(title);
        cy.get('textarea[name="description"]').type('Authorization test post.');
        cy.get('textarea[name="required_skills"]').type('PHP');
        cy.get('input[name="location"]').type('Hamilton');
        cy.contains('button', 'Create Collaboration').click();

        cy.url().should('include', '/posts.php');

        // Capture the post ID while logged in as its owner.
        cy.contains('h3.post-title', title)
            .closest('.post-card')
            .find('a.edit-btn')
            .invoke('attr', 'href')
            .then((href) => {
                const postId = new URL(href, window.location.origin)
                    .searchParams.get('id');

                expect(postId).to.exist;

                // Switch to User2's session.
                cy.clearCookies();
                cy.visit(`${baseUrl}/login.php`);
                cy.get('input[name="email"]').type('user2@testing.com');
                cy.get('input[name="password"]').type('Username@12');
                cy.get('button[type="submit"]').click();
                cy.url().should('include', '/dashboard.php');

                // Obtain a valid CSRF token for User2's session.
                cy.visit(`${baseUrl}/post-create.php`);
                cy.get('input[name="csrf"]')
                    .should('exist')
                    .invoke('val')
                    .then((csrfToken) => {
                        // Attempt to delete User3's post using User2's session.
                        cy.request({
                            method: 'POST',
                            url: `${baseUrl}/post-delete.php`,
                            form: true,
                            body: {
                                post_id: postId,
                                csrf: csrfToken
                            },
                            failOnStatusCode: false
                        }).then((response) => {
                            expect(response.status).to.be.oneOf([200, 302]);
                        });
                    });

                // Verify that the owner's post still exists.
                cy.visit(`${baseUrl}/posts.php`);
                cy.contains('h3.post-title', title).should('be.visible');
            });
    });

    it('TC-POST6 - Logged-out visitor cannot access the create post page', () => {
        cy.clearCookies();

        cy.visit(`${baseUrl}/post-create.php`);

        // A visitor should be redirected to the login page.
        cy.url().should('include', '/login.php');
    });


});
