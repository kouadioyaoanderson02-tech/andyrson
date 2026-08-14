// Token management
const Auth = {
    getToken: () => localStorage.getItem('jwt_token'),
    setToken: (token) => localStorage.setItem('jwt_token', token),
    removeToken: () => localStorage.removeItem('jwt_token'),
    getHeaders: () => ({
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${Auth.getToken()}`
    })
};

// API helper
async function apiCall(url, method = 'GET', body = null) {
    const options = { method, headers: Auth.getHeaders() };
    if (body) options.body = JSON.stringify(body);
    const res = await fetch(url, options);
    if (!res.ok) throw await res.json();
    return res.json();
}
