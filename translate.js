const fs = require('fs');
const path = require('path');

const translations = [
  // Estimator & Forms
  ["Tidak ada fitur tambahan khusus untuk layanan ini.", "There are no specific add-on features for this service."],
  ["Tidak ada", "None"],
  ["Kalkulator Harga", "Price Estimator"],
  ["Pilih layanan, tingkat desain, dan fitur tambahan yang Anda butuhkan. Dapatkan estimasi harga instan untuk proyek digital Anda.", "Choose the service, design tier, and add-on features you need. Get an instant price estimate for your digital project."],
  ["Estimasi Total Biaya", "Total Estimated Cost"],
  ["\\* Harga ini hanya estimasi awal dan bisa berubah sesuai kompleksitas riil proyek.", "* This price is only an initial estimate and may change depending on the actual project complexity."],
  ["Sesi Konsultasi Gratis", "Free Consultation Session"],
  ["Ngobrol santai seputar ide Anda. Kami bantu buatkan strategi dasar tanpa biaya atau komitmen apapun di awal.", "A casual chat about your ideas. We help build a basic strategy without any upfront cost or commitment."],
  ["Pilih Layanan Utama", "Select Main Service"],
  ["Tingkat Design", "Design Tier"],
  ["Fitur Tambahan (Opsional)", "Add-on Features (Optional)"],
  ["Data Diri Anda", "Your Details"],
  ["Nama Lengkap \\*", "Full Name *"],
  ["WhatsApp \\*", "WhatsApp *"],
  ["Ceritakan detail proyek Anda secara singkat \\*", "Briefly tell us about your project details *"],
  ["Kirim Pesan via WhatsApp", "Send Message via WhatsApp"],
  
  // Pricing
  ["Daftar Paket Harga", "Pricing Plans"],
  ["Belum ada paket harga.", "No pricing plans available."],
  ["Harga transparan, tanpa biaya tersembunyi. Pilih paket yang paling sesuai dengan kebutuhan bisnis Anda.", "Transparent pricing, no hidden fees. Choose the plan that best fits your business needs."],
  
  // FAQ
  ["Pertanyaan yang paling sering kami terima dari calon klien. Tidak menemukan jawaban yang Anda cari\\? Langsung hubungi kami.", "The most frequent questions we receive from potential clients. Can't find the answer you're looking for? Contact us directly."],
  ["Pertanyaan Umum", "Frequently Asked Questions"],
  
  // Footer
  ["Tautan Penting", "Quick Links"],
  ["Layanan Kami", "Our Services"],
  ["Ikuti Kami", "Follow Us"],
  ["Hak Cipta Dilindungi", "All Rights Reserved"],
  
  // Portfolio
  ["Karya \\& <span className=\"text-\\[#C7F236\\] font-serif italic\">Portofolio<\\/span>", "Works & <span className=\"text-[#C7F236] font-serif italic\">Portfolio</span>"],
  ["Jelajahi portofolio studi kasus produk digital rancangan kami. Kami memadukan kode berkualitas tinggi dengan pengalaman visual yang memikat.", "Explore our portfolio of digital product case studies. We blend high-quality code with captivating visual experiences."],
  ["Website resmi", "Official website of"],
  ["Landing page UMKM kuliner", "Culinary SME landing page"],
  ["Hubungi kami sekarang untuk berdiskusi langsung mengenai konsep, teknologi, dan biaya estimasi proyek baru Anda.", "Contact us now to directly discuss the concept, technology, and estimated cost for your new project."],
  ["Lihat Portofolio", "View Portfolio"],
  ["Hubungi Kami", "Contact Us"],
  
  // Services
  ["Solusi \\& <span className=\"text-\\[#C7F236\\] font-serif italic\">Layanan<\\/span>", "Solutions & <span className=\"text-[#C7F236] font-serif italic\">Services</span>"],
  ["Tanya Layanan Ini", "Inquire About This Service"],
  ["Harga Mulai Dari", "Starting Price"],
  ["Tingkatkan kredibilitas bisnis Anda dengan website profil perusahaan yang profesional.", "Boost your business credibility with a professional company profile website."],
  
  // Contact
  ["Ada ide besar yang ingin diwujudkan atau pertanyaan mengenai layanan kami\\? Sampaikan pesan Anda, kami siap berkolaborasi.", "Have a big idea you want to realize or questions about our services? Send your message, we're ready to collaborate."],
  ["Kami percaya produk digital yang hebat berawal dari pemahaman mendalam tentang masalah yang ingin diselesaikan. Isi formulir konsultasi dan tim kami akan segera menghubungi Anda.", "We believe great digital products start with a deep understanding of the problem to be solved. Fill out the consultation form and our team will contact you shortly."],
  ["Komitmen Pelayanan Kami", "Our Service Commitment"],
  ["Layanan yang Dibutuhkan \\*", "Required Service *"],
  ["Tentu. Kami menghargai inovasi bisnis Anda. Kami sangat terbuka dan siap untuk menandatangani Non-Disclosure Agreement \\(NDA\\) sebelum Anda membagikan detail rahasia proyek.", "Absolutely. We value your business innovation. We are very open and ready to sign a Non-Disclosure Agreement (NDA) before you share any confidential project details."],
];

function walk(dir, callback) {
  fs.readdirSync(dir).forEach(f => {
    let dirPath = path.join(dir, f);
    let isDirectory = fs.statSync(dirPath).isDirectory();
    isDirectory ? walk(dirPath, callback) : callback(path.join(dir, f));
  });
}

walk('./src', function(filePath) {
  if (filePath.endsWith('.tsx') || filePath.endsWith('.ts')) {
    let content = fs.readFileSync(filePath, 'utf8');
    let original = content;
    translations.forEach(([indo, eng]) => {
      const regex = new RegExp(indo, 'g');
      content = content.replace(regex, eng);
    });
    
    // Hardcoded replacements for message strings
    content = content.replace(/`Halo tim JuangDev, saya ingin konsultasi mengenai proyek baru.\\n\\n\*Estimasi Pilihan:\*\\n- Layanan: \$\{serviceName\}\\n- Tingkat Design: \$\{tierName\}\\n- Fitur Tambahan: \$\{featureNames\}\\n\*Estimasi Harga:\* Rp \$\{totalPrice\.toLocaleString\('id-ID'\)\}\\n\\n\*Nama:\* \$\{formData\.name\}\\n\*WhatsApp:\* \$\{formData\.phone\}\\n\*Email:\* \$\{formData\.email \|\| '-'\}\\n\\n\*Detail Proyek:\*\\n\$\{formData\.details\}`/g, 
    "`Hello JuangDev team, I'd like to consult about a new project.\\n\\n*Selected Estimates:*\\n- Service: ${serviceName}\\n- Design Tier: ${tierName}\\n- Add-on Features: ${featureNames}\\n*Estimated Price:* Rp ${totalPrice.toLocaleString('id-ID')}\\n\\n*Name:* ${formData.name}\\n*WhatsApp:* ${formData.phone}\\n*Email:* ${formData.email || '-'}\\n\\n*Project Details:*\\n${formData.details}`");

    content = content.replace(/`Halo JuangDev, saya ingin konsultasi proyek baru.\\n\\n\*Layanan yang Dibutuhkan:\* \$\{selectedService\}\\n\*Nama Lengkap:\* \$\{formData\.name\}\\n\*Nomor WA:\* \$\{formData\.whatsapp\}\\n\*Email:\* \$\{formData\.email \|\| \"-\"\}\\n\\n\*Gambaran Proyek:\*\\n\$\{formData\.description\}`/g,
    "`Hello JuangDev, I would like to consult about a new project.\\n\\n*Required Service:* ${selectedService}\\n*Full Name:* ${formData.name}\\n*WhatsApp:* ${formData.whatsapp}\\n*Email:* ${formData.email || \"-\"}\\n\\n*Project Overview:*\\n${formData.description}`");

    if (content !== original) {
      fs.writeFileSync(filePath, content, 'utf8');
      console.log('Translated: ' + filePath);
    }
  }
});
