
const baseUrl = 'http://localhost/559-Capstone-Mohawk-capstone-LocalConnect';

describe('LocalConnect Search, Filter and Sort', () => {
    beforeEach(() => {
        cy.visit(`${baseUrl}/login.php`);

        cy.get('input[name="email"]').type('User3@example.com');
        cy.get('input[name="password"]').type('Testing@12');

        cy.get('button[type="submit"]').click();
        cy.url().should('include', '/dashboard.php');
    });

    it('TC-F5-01 - User can search collaboration posts by title', () => {
        const title = `Cypress Search Title ${Date.now()}`;

        // Create a test collaboration.
        cy.visit(`${baseUrl}/post-create.php`);
        cy.get('input[name="title"]').type(title);
        cy.get('textarea[name="description"]').type('Search testing description.');
        cy.get('textarea[name="required_skills"]').type('PHP');
        cy.get('input[name="location"]').type('Hamilton');
        cy.contains('button', 'Create Collaboration').click();

        // Search for the unique title.
        cy.visit(`${baseUrl}/posts.php`);
        cy.get('[data-testid="search-input"]').type(title);
        cy.get('[data-testid="search-apply-button"]').click();

        cy.contains('h3.post-title', title).should('be.visible');
    });

    it('TC-F5-02 - User can search posts by description keywords', () => {
        const keyword = `CypressKeyword${Date.now()}`;
        const title = `Description Search ${Date.now()}`;

        cy.visit(`${baseUrl}/post-create.php`);
        cy.get('input[name="title"]').type(title);
        cy.get('textarea[name="description"]').type(`This post requires ${keyword} experience.`);
        cy.get('textarea[name="required_skills"]').type('PHP');
        cy.get('input[name="location"]').type('Hamilton');
        cy.contains('button', 'Create Collaboration').click();

        cy.visit(`${baseUrl}/posts.php`);
        cy.get('[data-testid="search-input"]').type(keyword);
        cy.get('[data-testid="search-apply-button"]').click();

        cy.contains('h3.post-title', title).should('be.visible');
    });

    it('TC-F5-03 - User can search posts by required skills', () => {
        const skill = `CypressSkill${Date.now()}`;
        const title = `Skill Search ${Date.now()}`;

        cy.visit(`${baseUrl}/post-create.php`);
        cy.get('input[name="title"]').type(title);
        cy.get('textarea[name="description"]').type('Testing required skills search.');
        cy.get('textarea[name="required_skills"]').type(skill);
        cy.get('input[name="location"]').type('Hamilton');
        cy.contains('button', 'Create Collaboration').click();

        cy.visit(`${baseUrl}/posts.php`);
        cy.get('[data-testid="search-input"]').type(skill);
        cy.get('[data-testid="search-apply-button"]').click();

        cy.contains('h3.post-title', title).should('be.visible');
    });

    it('TC-F5-04 - User can filter posts by location', () => {
        const title = `Location Filter ${Date.now()}`;

        cy.visit(`${baseUrl}/post-create.php`);
        cy.get('input[name="title"]').type(title);
        cy.get('textarea[name="description"]').type('Testing location filter.');
        cy.get('textarea[name="required_skills"]').type('PHP');
        cy.get('input[name="location"]').type('Hamilton');
        cy.contains('button', 'Create Collaboration').click();

        cy.visit(`${baseUrl}/posts.php`);
        cy.get('[data-testid="filter-location-select"]').select('Hamilton');
        cy.get('[data-testid="search-apply-button"]').click();

        cy.contains('h3.post-title', title).should('be.visible');
    });

    it('TC-F5-05 - User can filter posts by required skills', () => {
        const skill = `CypressFilterSkill${Date.now()}`;
        const title = `Required Skills Filter ${Date.now()}`;

        cy.visit(`${baseUrl}/post-create.php`);
        cy.get('input[name="title"]').type(title);
        cy.get('textarea[name="description"]').type('Testing skills filter.');
        cy.get('textarea[name="required_skills"]').type(skill);
        cy.get('input[name="location"]').type('Hamilton');
        cy.contains('button', 'Create Collaboration').click();

        cy.visit(`${baseUrl}/posts.php`);
        cy.get('[data-testid="filter-skill-select"]').select(skill);
        cy.get('[data-testid="search-apply-button"]').click();

        cy.contains('h3.post-title', title).should('be.visible');
    });

    it('TC-F5-06 - User can sort posts by oldest date', () => {
        cy.visit(`${baseUrl}/posts.php`);

        cy.get('[data-testid="sort-select"]').select('date_asc');
        cy.get('[data-testid="search-apply-button"]').click();

        cy.url().should('include', 'sort=date_asc');
        cy.get('[data-testid="posts-grid"]').should('be.visible');
    });

    it('TC-F5-07 - User can sort posts by location', () => {
        cy.visit(`${baseUrl}/posts.php`);

        cy.get('[data-testid="sort-select"]').select('location');
        cy.get('[data-testid="search-apply-button"]').click();

        cy.url().should('include', 'sort=location');
        cy.get('[data-testid="posts-grid"]').should('be.visible');
    });

    it('TC-F5-08 - User can clear search and filters', () => {
        cy.visit(`${baseUrl}/posts.php`);

        cy.get('[data-testid="search-input"]').type('CypressTest');
        cy.get('[data-testid="search-apply-button"]').click();

        cy.get('[data-testid="clear-filters-link"]').click();

        cy.url().should('include', '/posts.php');
        cy.get('[data-testid="search-input"]').should('have.value', '');
        cy.get('[data-testid="filter-location-select"]').should('have.value', '');
        cy.get('[data-testid="filter-skill-select"]').should('have.value', '');
    });
});
