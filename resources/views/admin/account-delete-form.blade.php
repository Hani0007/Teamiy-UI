<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Delete Your TeamiY Account</title>
    <style>
      body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background: #f9f9fb;
        color: #333;
        margin: 0;
        padding: 2rem;
        line-height: 1.6;
      }
      .container {
        max-width: 700px;
        margin: auto;
        background: white;
        padding: 2.5rem;
        border-radius: 14px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
      }
      .logo {
        display: block;
        margin: 0 auto 1.5rem;
        height: 60px;
      }
      h1 {
        color: #1a73e8;
        text-align: center;
        margin-top: 0.5rem;
        font-size: 1.8rem;
      }
      .highlight {
        background: #e8f0fe;
        padding: 1.2rem;
        border-left: 5px solid #1a73e8;
        margin: 1.8rem 0;
        border-radius: 0 8px 8px 0;
      }
      ul {
        margin: 1rem 0;
        padding-left: 1.6rem;
      }
      .form-group {
        margin: 1.8rem 0;
      }
      label {
        display: block;
        margin-bottom: 0.6rem;
        font-weight: 600;
        color: #444;
      }
      input[type="text"] {
        width: 100%;
        padding: 0.9rem;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 1rem;
        box-sizing: border-box;
      }
      button {
        background: #d93025;
        color: white;
        border: none;
        padding: 0.9rem 2rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        transition: background 0.2s;
      }
      button:hover {
        background: #b00020;
      }
      .note {
        font-size: 0.92rem;
        color: #555;
        margin-top: 1.8rem;
        background: #f5f5f5;
        padding: 1rem;
        border-radius: 8px;
      }
      footer {
        text-align: center;
        margin-top: 3rem;
        color: #777;
        font-size: 0.85rem;
      }
      footer a {
        color: #1a73e8;
        text-decoration: none;
      }
      footer a:hover {
        text-decoration: underline;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <!-- Logo -->
      <img
        src="https://teamiy.com/wp-content/uploads/2025/08/teamiy-logo.webp"
        alt="TeamiY Logo"
        class="logo"
      />

      <h1>Delete Your TeamiY Account</h1>
      <p style="text-align: center">
        We're sad to see you go. Permanently delete your
        <strong>TeamiY</strong> account and all associated data below.
      </p>

      <div class="highlight">
        <strong>Warning:</strong> This action <strong>cannot be undone</strong>.
        All your data will be erased permanently.
      </div>

      <h2>How to Request Deletion</h2>
      <ol>
        <li>
          Enter your <strong>email</strong> or
          <strong>TeamiY User ID</strong> below.
        </li>
        <li>We’ll send a secure confirmation link to your email.</li>
        <li>
          Click the link within <strong>48 hours</strong> to confirm deletion.
        </li>
      </ol>

      <form action="{{ route('admin.delete_request') }}" method="POST">
        @csrf

        <div class="form-group">
          <label for="identifier">Email *</label>
          <input
            type="text"
            id="identifier"
            name="identifier"
            placeholder="you@example.com"
            required
          />
        </div>

        <button type="submit">Send Deletion Request</button>
      </form>

      <div class="note">
        <p><strong>After requesting:</strong></p>
        <ul>
          <li>Confirmation email arrives in < 5 minutes</li>
          <li>Link expires in <strong>48 hours</strong></li>
          <li>Deletion starts immediately upon confirmation</li>
          <li>Final confirmation email sent within 24 hours</li>
        </ul>
      </div>

      {{-- <h2>Data Deletion Details</h2>

      <h3>Data Permanently Deleted</h3>
      <ul>
        <li>Profile (name, photo, bio, team roles)</li>
        <li>Messages, team chats, files, and attachments</li>
        <li>Team invites, memberships, and activity logs</li>
        <li>Preferences, notifications, and app settings</li>
        <li>Authentication tokens and session history</li>
      </ul>

      <h3>Data Retained (Anonymized or Legally Required)</h3>
      <ul>
        <li>
          <strong>Anonymized analytics</strong>: Kept for
          <strong>30 days</strong> for security & debugging, then deleted.
        </li>
        <li>
          <strong>Financial transaction records</strong> (if any): Retained for
          <strong>7 years</strong> per tax law.
        </li>
        <li>
          <strong>Legal compliance data</strong>: May be retained if required by
          law or court order.
        </li>
      </ul> --}}

      <footer>
        &copy; 2025 TeamiY. All rights reserved.<br />
        Need help? Email
        <a href="mailto:support@teamiy.com">support@teamiy.com</a>
      </footer>
    </div>
  </body>
</html>
