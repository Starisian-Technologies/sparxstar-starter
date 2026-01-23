/**
 * Example Jest test file
 *
 * This demonstrates basic Jest testing patterns.
 * Create test files in __tests__ directories or with .test.js/.spec.js suffixes.
 */

describe('Example Test Suite', () => {
    test('basic arithmetic works', () => {
        expect(1 + 1).toBe(2);
    });

    test('arrays contain expected values', () => {
        const fruits = ['apple', 'banana', 'orange'];
        expect(fruits).toContain('banana');
        expect(fruits).toHaveLength(3);
    });

    test('objects have expected properties', () => {
        const user = {
            name: 'John',
            email: 'john@example.com',
        };

        expect(user).toHaveProperty('name');
        expect(user.name).toBe('John');
    });
});

/**
 * Example function to test
 */
function greet(name) {
    return `Hello, ${name}!`;
}

describe('greet function', () => {
    test('returns proper greeting', () => {
        expect(greet('World')).toBe('Hello, World!');
    });

    test('handles empty string', () => {
        expect(greet('')).toBe('Hello, !');
    });
});

/**
 * Example async test
 */
async function fetchData() {
    return new Promise((resolve) => {
        setTimeout(() => resolve('data'), 100);
    });
}

describe('async operations', () => {
    test('fetches data successfully', async () => {
        const data = await fetchData();
        expect(data).toBe('data');
    });
});

/**
 * Example DOM testing (if testing with jsdom)
 */
describe('DOM manipulation', () => {
    test('creates and appends element', () => {
        document.body.innerHTML = '<div id="app"></div>';

        const app = document.getElementById('app');
        const p = document.createElement('p');
        p.textContent = 'Hello DOM';
        app.appendChild(p);

        expect(app.children.length).toBe(1);
        expect(app.querySelector('p').textContent).toBe('Hello DOM');
    });
});
