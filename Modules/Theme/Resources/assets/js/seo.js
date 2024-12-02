export default function seo() {
    const seoData = document.getElementById('seo-data');

    if (!seoData) {
        console.warn('SEO data element not found');
        return;
    }

    const seoDescription = seoData.getAttribute('data-seo-description') || '';
    const seoKeywords = seoData.getAttribute('data-seo-keyword') || '';

    const formattedKeywords = seoKeywords
        .split(',')
        .map(keyword => keyword.trim())
        .filter((keyword, index, self) => keyword && self.indexOf(keyword) === index)
        .join(', ');

    const metaTitle = document.querySelector('meta[name="title"]');
    let seoTitle = null;

    if (metaTitle && metaTitle.getAttribute('content').trim() !== '') {
        seoTitle = metaTitle.getAttribute('content');
    }
    
    if (seoTitle !== null) {
        document.title = seoTitle;
    } else {
        document.title = 'Trang chưa có tiêu đề';
    }

    let h1 = document.querySelector('h1.seo-title');

    if (!h1) {
        h1 = document.createElement('h1');
        h1.className = 'seo-title';
        document.body.insertBefore(h1, document.body.firstChild);
    }
    h1.textContent = seoTitle;
    h1.style.display = 'none';

    updateMetaTag('description', seoDescription);
    updateMetaTag('keywords', formattedKeywords);
}

function updateMetaTag(name, content, attributeName = 'name') {
    if (!content) return;

    let meta = document.querySelector(`meta[${attributeName}="${name}"]`);

    if (meta) {
        meta.setAttribute('content', content);
    } else {
        meta = document.createElement('meta');
        meta.setAttribute(attributeName, name);
        meta.setAttribute('content', content);
        document.head.appendChild(meta);
    }
}