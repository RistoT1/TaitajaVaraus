import logoImg from '../../images/image.png';
import '../../styles/footer.css'

function Footer() {
  return (
    <footer className="footer">
      <div className="footer-container">
        <div className="footer-content">
          {/* Logo/Brand Section */}
          <div className="footer-section">
            <div className="footer-logo">
              <img src={logoImg} alt="joo" />
            </div>
            <p className="footer-description">
              Your tagline or brief description goes here.
            </p>
          </div>

          {/* Links Section */}
          <div className="footer-section">
            <h3>Quick Links</h3>
            <ul>
              <li><a href="#">Home</a></li>
              <li><a href="#">About</a></li>
              <li><a href="#">Services</a></li>
              <li><a href="#">Contact</a></li>
            </ul>
          </div>

          {/* Contact Section */}
          <div className="footer-section">
            <h3>Contact</h3>
            <ul>
              <li>Email: info@example.com</li>
              <li>Phone: +358 123 456 789</li>
              <li>Address: Helsinki, Finland</li>
            </ul>
          </div>
        </div>

        {/* Bottom Bar */}
        <div className="footer-bottom">
          <p>&copy; 2026 Your Company. All rights reserved.</p>
        </div>
      </div>
    </footer>
  )
}

export default Footer