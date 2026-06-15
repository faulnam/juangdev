const fs = require('fs');
const path = require('path');

const translations = [
  // Remaining texts
  ["Halo tim JuangDev, saya ingin konsultasi mengenai proyek baru.", "Hello JuangDev team, I'd like to consult about a new project."],
  ["Estimasi Pilihan", "Selected Estimates"],
  ["Tingkat Design", "Design Tier"],
  ["Fitur Tambahan", "Add-on Features"],
  ["Estimasi Harga", "Estimated Price"],
  ["Nama:", "Name:"],
  ["Detail Proyek:", "Project Details:"],
  ["Mulai Dari", "Starting From"],
  ["Layanan pembuatan website, aplikasi mobile, dan desain UI/UX dari JuangDev.", "Website development, mobile app, and UI/UX design services by JuangDev."],
  ["Jelajahi portofolio studi kasus produk digital rancangan JuangDev.", "Explore the portfolio of digital product case studies designed by JuangDev."],
  ["Pilih Jenis Layanan", "Select Service Type"],
  ["Halo JuangDev, saya telah mencoba kalkulator estimasi proyek.", "Hello JuangDev, I have tried the project estimation calculator."],
  ["Total Estimasi", "Total Estimate"],
  ["Saya ingin konsultasi lebih lanjut mengenai ini.", "I would like to consult further regarding this."],
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

    if (content !== original) {
      fs.writeFileSync(filePath, content, 'utf8');
      console.log('Translated: ' + filePath);
    }
  }
});
