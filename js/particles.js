// particles.js - оптимизированные анимированные лапки
class PawParticles {
    constructor() {
        // Проверяем, не мобильное ли устройство
        if (window.innerWidth < 768) {
            return; // Отключаем на мобилках
        }
        
        this.canvas = null;
        this.ctx = null;
        this.particles = [];
        this.animationId = null;
        this.isRunning = false;
        this.lastTimestamp = 0;
        this.pawEmojis = ['🐾', '🐱'];
        
        this.init();
    }
    
    init() {
        this.canvas = document.createElement('canvas');
        this.canvas.id = 'particles-canvas';
        document.body.appendChild(this.canvas);
        
        this.ctx = this.canvas.getContext('2d');
        this.resize();
        
        window.addEventListener('resize', () => this.resize());
        
        // Запускаем с задержкой, чтобы не мешать загрузке
        setTimeout(() => this.start(), 1000);
    }
    
    resize() {
        this.canvas.width = window.innerWidth;
        this.canvas.height = window.innerHeight;
    }
    
    createParticle() {
        return {
            x: Math.random() * this.canvas.width,
            y: this.canvas.height + Math.random() * 200,
            size: Math.random() * 16 + 12,
            speedX: (Math.random() - 0.5) * 0.3,
            speedY: -Math.random() * 1.5 - 0.5,
            opacity: Math.random() * 0.4 + 0.2,
            rotation: Math.random() * 360,
            emoji: this.pawEmojis[Math.floor(Math.random() * this.pawEmojis.length)]
        };
    }
    
    updateParticles() {
        // Меньше частиц для производительности
        if (this.particles.length < 20 && Math.random() < 0.2) {
            this.particles.push(this.createParticle());
        }
        
        for (let i = 0; i < this.particles.length; i++) {
            const p = this.particles[i];
            p.x += p.speedX;
            p.y += p.speedY;
            p.opacity -= 0.003;
            
            if (p.y < -100 || p.opacity <= 0) {
                this.particles.splice(i, 1);
                i--;
            }
        }
    }
    
    draw() {
        if (!this.ctx) return;
        
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        
        for (const p of this.particles) {
            this.ctx.save();
            this.ctx.translate(p.x, p.y);
            this.ctx.font = `${p.size}px "Segoe UI Emoji", "Apple Color Emoji"`;
            this.ctx.globalAlpha = p.opacity;
            this.ctx.fillStyle = '#FF9800';
            this.ctx.fillText(p.emoji, -p.size/2, p.size/2);
            this.ctx.restore();
        }
    }
    
    animate(timestamp) {
        if (!this.isRunning) return;
        
        // Ограничиваем FPS для производительности
        if (timestamp - this.lastTimestamp > 33) { // ~30 FPS
            this.updateParticles();
            this.draw();
            this.lastTimestamp = timestamp;
        }
        
        this.animationId = requestAnimationFrame((t) => this.animate(t));
    }
    
    start() {
        this.isRunning = true;
        this.animate(0);
    }
    
    stop() {
        this.isRunning = false;
        if (this.animationId) {
            cancelAnimationFrame(this.animationId);
        }
    }
}

// Запускаем только на главной странице и не на мобилках
if (window.innerWidth >= 768) {
    const currentPage = window.location.pathname;
    if (currentPage.includes('index.php') || currentPage === '/' || currentPage === '/index.php') {
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.pawParticles = new PawParticles();
            }, 500);
        });
    }
}