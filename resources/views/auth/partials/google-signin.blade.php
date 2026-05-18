@php
    $fb = config('services.firebase');
@endphp

<div id="fb-error" class="hidden mb-4 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300"></div>

<button type="button" id="google-signin-btn"
    class="w-full flex items-center justify-center gap-3 bg-white hover:bg-gray-100 text-gray-900 font-semibold py-3.5 rounded-xl transition-all hover:shadow-xl active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed">
    <svg class="w-5 h-5" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
        <path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3c-1.6 4.7-6.1 8-11.3 8-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.4-.4-3.5z"/>
        <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 16 19 13 24 13c3.1 0 5.9 1.2 8 3l5.7-5.7C34 6.1 29.3 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/>
        <path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.2l-6.2-5.2C29.1 35 26.7 36 24 36c-5.2 0-9.6-3.3-11.3-8l-6.5 5C9.6 39.6 16.2 44 24 44z"/>
        <path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.3-2.3 4.3-4.1 5.6l6.2 5.2C41.3 36.1 44 30.5 44 24c0-1.3-.1-2.4-.4-3.5z"/>
    </svg>
    <span id="google-signin-label">{{ $label ?? 'Continue with Google' }}</span>
</button>

<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
    import { getAuth, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";

    const firebaseConfig = {
        apiKey: @json($fb['api_key']),
        authDomain: @json($fb['auth_domain']),
        projectId: @json($fb['project_id']),
        appId: @json($fb['app_id']),
    };

    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);
    const provider = new GoogleAuthProvider();

    const btn = document.getElementById('google-signin-btn');
    const label = document.getElementById('google-signin-label');
    const errBox = document.getElementById('fb-error');
    const originalLabel = label.textContent;

    function showError(msg) {
        errBox.textContent = msg;
        errBox.classList.remove('hidden');
    }

    btn.addEventListener('click', async () => {
        errBox.classList.add('hidden');
        btn.disabled = true;
        label.textContent = 'Signing in...';
        try {
            const result = await signInWithPopup(auth, provider);
            const idToken = await result.user.getIdToken();

            const resp = await fetch(@json(route('auth.firebase.callback')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ id_token: idToken }),
            });

            if (!resp.ok) {
                const data = await resp.json().catch(() => ({}));
                throw new Error(data.message || 'Sign-in failed.');
            }
            const data = await resp.json();
            window.location.href = data.redirect || '/';
        } catch (e) {
            console.error(e);
            showError(e.message || 'Unable to sign in with Google.');
            btn.disabled = false;
            label.textContent = originalLabel;
        }
    });
</script>
