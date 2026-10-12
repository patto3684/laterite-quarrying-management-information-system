<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | LQMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #8c20ad;
            background: #081429;
        }

        .hero {
            position: relative;
            height: 100vh;
            display: grid;
            align-items: center;
            justify-items: center;
            overflow: hidden;
            padding-top: 72px;
            box-sizing: border-box;
        }

        .hero::before,
        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            will-change: opacity;
        }

        .hero::before {
            background-image: var(--hero-current-image, url('<?= APP_URL ?>/assets/images/activity1.jpg'));
            opacity: 1;
            z-index: 0;
        }

        .hero::after {
            background-image: var(--hero-next-image, url('<?= APP_URL ?>/assets/images/activity2.jpg'));
            opacity: 0;
            transition: opacity 0.5s ease;
            z-index: 1;
            pointer-events: none;
        }

        .hero.fade-image::after {
            opacity: 1;
        }

        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 18px 8px;
            background: rgba(6, 12, 27, 0.92);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            z-index: 1050;
        }

        .topbar-brand {
            color: #ffffff;
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            margin-left: 0;
        }

        .topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            width: 100%;
            padding: 0 4px;
        }

        .nav-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .nav-tab {
            border: none;
            background: rgba(255, 255, 255, 0.08);
            color: #f8fafc;
            padding: 10px 16px;
            border-radius: 16px;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.2s ease;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .nav-tab:hover,
        .nav-tab.active {
            background: rgba(255, 255, 255, 0.18);
            transform: translateY(-1px);
        }

        .modal-content {
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.16);
            backdrop-filter: blur(20px);
            color: #fff;
            border-radius: 24px;
        }

        .modal-header {
            border-bottom: none;
            padding-bottom: 0;
        }

        .modal-title {
            font-size: 1.1rem;
            letter-spacing: 0.03em;
            color: #f8fafc;
        }

        .modal-body {
            padding-top: 0.5rem;
            color: rgba(255, 255, 255, 0.88);
            line-height: 1.8;
        }

        .modal-footer {
            border-top: none;
            padding-top: 0;
        }

        @media (max-width: 768px) {
            .topbar {
                padding: 14px 18px;
            }

            .topbar-inner {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links {
                width: 100%;
                justify-content: center;
            }
        }

        .hero-overlay {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 24px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            align-items: center;
            justify-items: stretch;
            min-height: calc(100vh - 88px);
        }

        .login-panel {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-radius: 32px;
            box-shadow: 0 28px 64px rgba(0, 0, 0, 0.24);
            padding: 18px 20px;
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
        }

        .login-panel h3 {
            margin-bottom: 2px;
            font-size: 1.65rem;
            font-weight: 800;
            color: #ff69b4;
        }

        .login-panel p {
            margin-bottom: 8px;
            font-size: 0.94rem;
            color: rgba(255, 255, 255, 0.78);
            line-height: 1.45;
        }

        .login-panel .form-label {
            margin-bottom: 0.25rem;
            display: inline-block;
        }


        .login-panel .form-label {
            color: rgba(255, 255, 255, 0.82);
            font-weight: 600;
        }

        .login-panel .form-control {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #3336d3;
            border-radius: 16px;
            padding: 16px 18px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .login -panel .form-control:focus {
            border-color: rgba(56, 189, 248, 0.8);
            box-shadow: 0 0 0 0.2rem rgba(56, 189, 248, 0.18);
            outline: none;
            background: rgba(255, 255, 255, 0.16);
            color: #180bc7;
        }

        .login-panel .form-control:invalid {
            border-color: rgba(248, 113, 113, 0.4);
            box-shadow: 0 0 0 1px rgba(248, 113, 113, 0.12);
            background: rgba(255, 255, 255, 0.14);
        }

        .login-panel .form-control:invalid:focus {
            background: rgba(255, 255, 255, 0.18);
        }

        .login-panel .form-control.is-invalid {
            border-color: rgba(248, 113, 113, 0.4);
            box-shadow: 0 0 0 1px rgba(248, 113, 113, 0.12);
            background: rgba(255, 255, 255, 0.14);
        }

        .login-panel .form-control:invalid::-webkit-validation-bubble-message,
        .login-panel .form-control:invalid::-webkit-textfield-decoration-container,
        .login-panel .form-control:invalid::-ms-input-placeholder {
            display: none;
        }

        .login-panel .invalid-feedback {
            color: #f5c5c0;
        }

        .login-panel .forgot-password-link {
            display: inline-block;
            margin-top: 0.4rem;
            color: #e0e0e0;
            font-size: 0.875rem;
            text-decoration: none;
        }

        .login-panel .forgot-password-link:hover {
            text-decoration: underline;
        }

        .login-panel .btn-primary {
            border-radius: 16px;
            padding: 10px 16px;
            font-weight: 700;
            background: #1d4ed8;
            border: none;
            box-shadow: 0 18px 36px rgba(29, 78, 216, 0.25);
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
            margin-top: 6px;
        }

        .login-panel .btn-primary:hover,
        .login-panel .btn-primary:focus {
            background-color: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 22px 42px rgba(37, 99, 235, 0.28);
        }

        .login-panel .btn-primary:active {
            transform: translateY(0);
        }

        .login-panel .alert {
            background: rgba(248, 113, 113, 0.14);
            border: 1px solid rgba(248, 113, 113, 0.28);
            color: #fee2e2;
        }

        .hero-copy {
            color: #ffffff;
            max-width: 520px;
            padding: 18px 0;
        }

        .hero-copy h1 {
            font-size: clamp(2.5rem, 4.5vw, 4.2rem);
            margin: 0 0 18px;
            line-height: 1.02;
            letter-spacing: -0.04em;
        }

        .hero-copy p {
            font-size: 1.02rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.82);
            max-width: 500px;
            margin-bottom: 22px;
        }

        .hero-copy .subtext {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.92rem;
            color: rgba(255, 255, 255, 0.72);
        }

        .hero-copy .subtext::before {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(59, 130, 246, 0.9);
        }

        @media (max-width: 992px) {
            .hero-overlay {
                grid-template-columns: 1fr;
                padding: 32px;
                gap: 32px;
                justify-items: center;
                text-align: center;
            }

            .hero-copy h1 {
                font-size: 3rem;
            }

            .hero-copy,
            .login-panel {
                max-width: 720px;
            }
        }

        @media (max-width: 720px) {
            .hero-overlay {
                padding: 24px;
                gap: 24px;
            }

            .login-panel {
                padding: 32px;
                width: 100%;
            }

            .hero-copy {
                padding: 0;
            }

            .hero-copy h1 {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
<div class="hero">
    <nav class="topbar">
        <div class="topbar-inner">
            <div class="d-flex align-items-center topbar-brand">
                <img src="<?= APP_URL ?>/assets/images/activity6.png" alt="Gasabo Laterite Quarry Logo" height="75" width="75" class="object-fit-contain" style="object-fit: contain; margin-left: 0;">
                <div class="ms-0 fs-5 fw-bold">Gasabo District Laterite Quarry Management Information System</div>
            </div>
            <div class="nav-links">
                <button class="nav-tab" data-bs-toggle="modal" data-bs-target="#infoModal" data-tab="overview">Overview & Geology</button>
                <button class="nav-tab" data-bs-toggle="modal" data-bs-target="#infoModal" data-tab="process">Mining Process</button>
                <button class="nav-tab" data-bs-toggle="modal" data-bs-target="#infoModal" data-tab="impact">Economic Impact</button>
            </div>
        </div>
    </nav>
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">Overview & Geology</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="infoModalBody">
                    Laterite is a highly weathered soil and rock type rich in iron and aluminum, extensively distributed across Rwanda. In the Gasabo district, it serves as a critical, high-strength raw material for local road foundations, construction fill, and brick manufacturing.
                </div>
            </div>
        </div>
    </div>
    <div class="hero-overlay">
        <div class="hero-copy">
            <h1>Power your quarry operations with a single login</h1>
            <p>Use LQMS to keep track of quarries, equipment, inspections, production, finance, and stock levels in one secure dashboard.</p>
            <div class="subtext">Fast access, clean visuals, and a responsive workflow for field and office teams.</div>
        </div>
        <div class="login-panel">
            <h3>Welcome</h3>
            <p>Sign in to access your quarry management dashboard and continue managing operations, inspections, finance, and stock data.</p>
            <?php if (!empty($error ?? '')): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post" class="needs-validation" novalidate>
                <div class="mb-2">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>
                <div class="mb-2">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                    <div class="invalid-feedback">Please enter your password.</div>
                    <a href="#" id="forgotPasswordLink" class="forgot-password-link">Forgot password?</a>
                </div>
                <button class="btn btn-primary w-100" type="submit">Login</button>
            </form>
            <p class="form-note mt-2">Need an account? <a href="<?= APP_URL ?>/register" class="text-decoration-none" style="color: #bfdbfe;">Create one here</a>.</p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()

    var modalElement = document.getElementById('infoModal')
    var modalTitle = document.getElementById('infoModalLabel')
    var modalBody = document.getElementById('infoModalBody')
    var tabs = document.querySelectorAll('.nav-tab')

    var tabContent = {
        overview: {
            title: 'Overview & Geology',
            description: 'Laterite is a highly weathered soil and rock type rich in iron and aluminum, extensively distributed across Rwanda. In the Gasabo district, it serves as a critical, high-strength raw material for local road foundations, construction fill, and brick manufacturing.'
        },
        process: {
            title: 'Mining Process',
            description: 'The extraction workflow involves topsoil clearing, open-cast excavation using heavy machinery, and onsite sorting. Extracted materials are stockpiled, quality-checked, and loaded onto haulage trucks via integrated weighbridges to track total tonnage.'
        },
        impact: {
            title: 'Economic Impact',
            description: 'Laterite quarrying drives the infrastructure boom in Kigali by supplying low-cost, durable materials. The industry generates direct employment for field operators and logistics providers while contributing local district regulatory fees to Gasabo.'
        }
    };

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            var key = tab.getAttribute('data-tab');
            var content = tabContent[key];
            if (!content) return;
            modalTitle.textContent = content.title;
            modalBody.textContent = content.description;
        })
    })

    var hero = document.querySelector('.hero');
    var heroImages = [
        '<?= APP_URL ?>/assets/images/activity1.jpg',
        '<?= APP_URL ?>/assets/images/activity2.jpg',
        '<?= APP_URL ?>/assets/images/activity3.jpg',
        '<?= APP_URL ?>/assets/images/activity4.jpg',
        '<?= APP_URL ?>/assets/images/activity5.jpg'
    ];
    var currentHeroIndex = 1;

    hero.style.setProperty('--hero-current-image', "url('" + heroImages[0] + "')");
    hero.style.setProperty('--hero-next-image', "url('" + heroImages[1] + "')");

    function cycleHeroImage() {
        var nextImage = heroImages[currentHeroIndex];
        hero.style.setProperty('--hero-next-image', "url('" + nextImage + "')");
        hero.classList.add('fade-image');

        setTimeout(function () {
            hero.style.setProperty('--hero-current-image', "url('" + nextImage + "')");
            hero.classList.remove('fade-image');
            currentHeroIndex = (currentHeroIndex + 1) % heroImages.length;
        }, 500);
    }

    setInterval(cycleHeroImage, 4000);

    var forgotPasswordLink = document.getElementById('forgotPasswordLink');
    if (forgotPasswordLink) {
        forgotPasswordLink.addEventListener('click', function (event) {
            event.preventDefault();
            alert('Self-service password reset is disabled. Please contact your system administrator to reset your password.');
        });
    }
</script>
</body>
</html>
