<?php
require_once __DIR__.'/includes/auth.php';
require_once __DIR__.'/includes/utils.php';
require_once __DIR__.'/includes/db.php';
require_auth();

$pdo = db();
$uid = current_user_id();

// Récupère ou initialise le CV
$stmt = $pdo->prepare("SELECT * FROM cvs WHERE user_id=? LIMIT 1");
$stmt->execute([$uid]);
$cv = $stmt->fetch();

if (!$cv) {
  $init = [
    'header' => [
      'full_name' => '', 'title' => '', 'email' => '', 'phone' => '', 'address' => ''
    ],
    'sections' => [
      ['type'=>'experience','items'=>[]],
      ['type'=>'education','items'=>[]],
      ['type'=>'skills','items'=>[]],
      ['type'=>'languages','items'=>[]]
    ]
  ];
  $pdo->prepare("INSERT INTO cvs(user_id,template_slug,data_json,updated_at) VALUES(?, 'classic', ?, NOW())")
      ->execute([$uid, json_encode($init, JSON_UNESCAPED_UNICODE)]);
  $cv = $pdo->query("SELECT * FROM cvs WHERE user_id=".(int)$uid." LIMIT 1")->fetch();
}

$data = json_decode($cv['data_json'] ?? '{}', true) ?: [];
$tpl  = $cv['template_slug'] ?? 'classic';

// Options Langues & Niveaux (CECR + Natif)
$langOptions = [
  'Arabe','Français','Anglais','Espagnol','Allemand','Italien','Portugais','Chinois (Mandarin)','Japonais','Turc','Russe','Néerlandais','Hindi'
];
$levelOptions = ['A1 Débutant','A2 Élémentaire','B1 Intermédiaire','B2 Intermédiaire avancé','C1 Avancé','C2 Maîtrise','Natif / Bilingue'];
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mon Dashboard — mycvtop</title>
  <style>
    :root{--green-50:#f0fdf4;--green-100:#dcfce7;--green-200:#bbf7d0;--green-300:#86efac;--green-400:#4ade80;--green-500:#22c55e;--green-600:#16a34a;--green-700:#15803d;--muted:#475569;--text:#0b1320;--border:#e2e8f0;--card:#ffffff}
    *{box-sizing:border-box}
    html,body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;color:var(--text);background:#fff}
    a{color:var(--green-700);text-decoration:none}
    .container{max-width:1200px;margin:0 auto;padding:0 16px}

    /* Topbar */
    .top{position:sticky;top:0;z-index:50;background:rgba(255,255,255,.9);backdrop-filter:saturate(180%) blur(10px);border-bottom:1px solid var(--border)}
    .top-inner{display:flex;align-items:center;justify-content:space-between;padding:12px 0}
    .brand{display:flex;align-items:center;gap:10px;color:var(--green-700);font-weight:800}
    .logo{width:32px;height:32px;border-radius:10px;display:grid;place-items:center;background:linear-gradient(135deg,var(--green-400),var(--green-700));color:#fff;font-weight:900}
    .actions{display:flex;gap:8px}
    .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:12px;border:1px solid var(--border);font-weight:700;min-height:40px}
    .btn-primary{background:var(--green-600);color:#fff;border-color:transparent}

    /* Layout */
    .wrap{display:grid;grid-template-columns: 1.1fr .9fr;gap:16px;padding:14px 0}
    .panel{background:var(--card);border:1px solid var(--border);border-radius:16px;box-shadow:0 12px 30px rgba(0,0,0,.05)}
    .panel-header{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid var(--border)}
    .panel-body{padding:16px}

    /* Tabs */
    .tabs{display:flex;flex-wrap:wrap;gap:8px;padding:12px 16px;border-bottom:1px solid var(--border)}
    .tab{padding:8px 12px;border-radius:10px;border:1px solid var(--border);background:#fff;cursor:pointer;font-weight:600}
    .tab.active{background:var(--green-100);border-color:var(--green-300);color:var(--green-700)}

    /* Forms */
    .label{display:block;margin:10px 0 6px;font-weight:600}
    .input,.select,textarea{width:100%;padding:12px 14px;border-radius:12px;border:1px solid var(--border)}
    textarea{min-height:90px;resize:vertical}
    .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
    .grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
    .row{display:flex;gap:8px;align-items:center}
    .chip{display:inline-flex;align-items:center;padding:6px 10px;border-radius:999px;border:1px solid var(--green-200);background:var(--green-50);cursor:pointer;margin:4px 6px 0 0}

    /* Items list */
    .item{border:1px dashed var(--border);border-radius:12px;padding:12px;margin:10px 0}
    .item-actions{display:flex;gap:8px;justify-content:flex-end}
    .btn-sm{padding:6px 10px;border-radius:10px;min-height:auto;font-weight:600}

    /* Preview */
    .preview-wrap{position:sticky;top:72px}
    .preview-head{display:flex;justify-content:space-between;align-items:center;padding:12px 16px;border-bottom:1px solid var(--border)}
    .preview-body{padding:0;height:75vh}
    .preview{width:100%;height:100%;border:0;border-bottom-left-radius:16px;border-bottom-right-radius:16px}

    /* Helpers */
    .muted{color:var(--muted)}
    .notice{background:#ecfeff;border:1px solid #bae6fd;padding:10px;border-radius:12px}
    .save-state{font-size:12px;color:var(--muted)}

    /* Mobile */
    @media (max-width: 980px){ .wrap{grid-template-columns:1fr} .preview-wrap{position:relative;top:auto} .preview-body{height:60vh} }
  </style>
</head>
<body>
<header class="top">
  <div class="container top-inner">
    <div class="brand"><span class="logo">m</span><span>mycvtop</span></div>
    <div class="actions" role="group">
      <a class="btn" href="preview.php">Aperçu</a>
      <a class="btn btn-primary" href="ads.php">Exporter PDF</a>
      <a class="btn" href="logout.php">Déconnexion</a>
    </div>
  </div>
</header>

<main class="container wrap">
  <!-- Éditeur -->
  <section class="panel" aria-label="Éditeur">
    <div class="panel-header">
      <strong>Construisez votre CV</strong>
      <div class="save-state" id="saveState">Auto‑sauvegarde…</div>
    </div>

    <div class="tabs" id="tabs">
      <div class="tab active" data-tab="profil">Profil</div>
      <div class="tab" data-tab="experience">Expériences</div>
      <div class="tab" data-tab="education">Formations</div>
      <div class="tab" data-tab="skills">Compétences</div>
      <div class="tab" data-tab="languages">Langues</div>
      <div class="tab" data-tab="design">Design & export</div>
    </div>

    <div class="panel-body" id="tab-profil">
      <div class="grid-2">
        <div>
          <label class="label">Nom complet</label>
          <input class="input" name="header.full_name" value="<?= h($data['header']['full_name'] ?? '') ?>">
        </div>
        <div>
          <label class="label">Titre (poste visé)</label>
          <input class="input" name="header.title" placeholder="Ex : Développeur Full‑Stack" value="<?= h($data['header']['title'] ?? '') ?>">
        </div>
      </div>
      <div class="grid-3">
        <div>
          <label class="label">Email</label>
          <input class="input" name="header.email" type="email" value="<?= h($data['header']['email'] ?? '') ?>">
        </div>
        <div>
          <label class="label">Téléphone</label>
          <input class="input" name="header.phone" placeholder="Ex : +212 6 .." value="<?= h($data['header']['phone'] ?? '') ?>">
        </div>
        <div>
          <label class="label">Adresse</label>
          <input class="input" name="header.address" placeholder="Ville, Pays" value="<?= h($data['header']['address'] ?? '') ?>">
        </div>
      </div>
      <p class="muted" style="margin-top:6px">Astuce : le titre doit correspondre au poste que vous ciblez (ex. « Technicien informatique »).</p>
    </div>

    <div class="panel-body" id="tab-experience" style="display:none">
      <div id="expList"></div>
      <button class="btn btn-sm" id="addExp">+ Ajouter une expérience</button>
      <div class="notice" style="margin-top:10px">Conseil : pour chaque expérience, décrivez 2–4 réalisations concrètes (ex. « amélioration du temps de chargement de 40% »).</div>
    </div>

    <div class="panel-body" id="tab-education" style="display:none">
      <div id="eduList"></div>
      <button class="btn btn-sm" id="addEdu">+ Ajouter une formation</button>
    </div>

    <div class="panel-body" id="tab-skills" style="display:none">
      <div id="skillList" class="row" style="flex-wrap:wrap"></div>
      <div style="margin-top:10px">
        <span class="muted">Suggestions :</span>
        <div>
          <?php foreach (["HTML","CSS","JavaScript","PHP","Laravel","MySQL","Git","Docker","Figma","Communication","Gestion de projet"] as $s): ?>
          <span class="chip" data-skill="<?= h($s) ?>"><?= h($s) ?></span>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="row" style="margin-top:10px"><input class="input" id="skillInput" placeholder="Ajouter une compétence"><button class="btn btn-sm" id="addSkill">Ajouter</button></div>
    </div>

    <div class="panel-body" id="tab-languages" style="display:none">
      <div id="langList"></div>
      <div class="row" style="margin-top:8px">
        <select class="select" id="langName">
          <option value="">Choisir une langue…</option>
          <?php foreach($langOptions as $lo): ?>
            <option value="<?= h($lo) ?>"><?= h($lo) ?></option>
          <?php endforeach; ?>
        </select>
        <select class="select" id="langLevel">
          <option value="">Niveau…</option>
          <?php foreach($levelOptions as $lv): ?>
            <option value="<?= h($lv) ?>"><?= h($lv) ?></option>
          <?php endforeach; ?>
        </select>
        <button class="btn btn-sm" id="addLang">Ajouter</button>
      </div>
      <p class="muted" style="margin-top:10px">Sélectionnez simplement la langue et le niveau (CECR). Ex. « Français — C1 Avancé », « Arabe — Natif/Bilingue ».</p>
    </div>

    <div class="panel-body" id="tab-design" style="display:none">
      <div class="grid-2">
        <div>
          <label class="label">Template</label>
          <select class="select" id="templateSelect">
            <option value="classic" <?= $tpl==='classic'?'selected':'' ?>>Classic</option>
            <option value="modern"  <?= $tpl==='modern' ?'selected':'' ?>>Modern</option>
          </select>
        </div>
        <div>
          <label class="label">Aperçu & Export</label>
          <div class="row"><a class="btn" href="preview.php">Ouvrir l’aperçu</a><a class="btn btn-primary" href="ads.php">Exporter PDF</a></div>
        </div>
      </div>
      <div class="notice" style="margin-top:12px">Astuce : restez concis (1 page si possible). Utilisez un langage clair et des verbes d’action.</div>
    </div>
  </section>

  <!-- Aperçu live -->
  <aside class="panel preview-wrap" aria-label="Aperçu en direct">
    <div class="preview-head"><strong>Aperçu</strong><span class="muted" id="progressTxt">Complétude : 0%</span></div>
    <div class="preview-body">
      <iframe class="preview" id="previewFrame" src="preview.php" title="Aperçu du CV"></iframe>
    </div>
  </aside>
</main>

<script>
// État initial JS
const cvId = <?= (int)$cv['id'] ?>;
let data = <?php echo json_encode($data, JSON_UNESCAPED_UNICODE); ?>;
const saveState = document.getElementById('saveState');
const preview = document.getElementById('previewFrame');
let saveTimer, previewTimer;

// Helpers
function touchPath(obj, path, value){ const keys = path.split('.'); let o=obj; for(let i=0;i<keys.length-1;i++){ if(!o[keys[i]]) o[keys[i]] = {}; o=o[keys[i]]; } o[keys[keys.length-1]] = value; }
function debounceSave(){ clearTimeout(saveTimer); saveTimer = setTimeout(save, 500); }
function refreshPreview(){ clearTimeout(previewTimer); previewTimer = setTimeout(()=> { preview.contentWindow.location.reload(); }, 700); }
function markSaving(){ saveState.textContent = 'Sauvegarde…'; }
function markSaved(){ const t = new Date().toLocaleTimeString(); saveState.textContent = 'Sauvegardé • '+t; }

// Auto‑save
function save(){
  const b = new URLSearchParams();
  b.set('cv_id', cvId);
  b.set('data_json', JSON.stringify(data));
  fetch('api/save_cv.php', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:b})
    .then(()=> { markSaved(); refreshPreview(); updateProgress(); })
    .catch(()=> saveState.textContent='Erreur de sauvegarde');
}

// TABS
const tabs = document.querySelectorAll('.tab');
const panels = {
  profil:    document.getElementById('tab-profil'),
  experience:document.getElementById('tab-experience'),
  education: document.getElementById('tab-education'),
  skills:    document.getElementById('tab-skills'),
  languages: document.getElementById('tab-languages'),
  design:    document.getElementById('tab-design')
};

tabs.forEach(t => t.addEventListener('click', ()=>{
  tabs.forEach(x=>x.classList.remove('active'));
  t.classList.add('active');
  Object.values(panels).forEach(p=>p.style.display='none');
  panels[t.dataset.tab].style.display='block';
}));

// PROFIL inputs
 document.querySelectorAll('#tab-profil .input').forEach(inp=>{
   inp.addEventListener('input', e=>{ markSaving(); touchPath(data, e.target.name, e.target.value); debounceSave(); });
 });

// EXPERIENCES
function renderExp(){
  data.sections = data.sections || [];
  let sec = data.sections.find(s=>s.type==='experience');
  if(!sec){ sec = {type:'experience', items:[]}; data.sections.push(sec); }
  const wrap = document.getElementById('expList'); wrap.innerHTML='';
  (sec.items||[]).forEach((it,idx)=>{
    const div = document.createElement('div');
    div.className='item';
    div.innerHTML = `
      <div class="grid-2">
        <div>
          <label class="label">Intitulé de poste</label>
          <input class="input" data-k="title" data-i="${idx}" value="${it.title||''}" placeholder="Ex: Développeur Web">
        </div>
        <div>
          <label class="label">Entreprise / Lieu</label>
          <input class="input" data-k="subtitle" data-i="${idx}" value="${it.subtitle||''}" placeholder="Ex: ACME, Rabat">
        </div>
      </div>
      <div class="grid-2">
        <div>
          <label class="label">Période</label>
          <input class="input" data-k="period" data-i="${idx}" value="${it.period||''}" placeholder="Ex: 2023 – 2025">
        </div>
        <div>
          <label class="label">Réalisations (2–4 lignes)</label>
          <textarea data-k="desc" data-i="${idx}" placeholder="Ex: Mise en place de ...\nOptimisation ...">${it.desc||''}</textarea>
        </div>
      </div>
      <div class="item-actions">
        <button class="btn btn-sm" data-move="up" data-i="${idx}">↑</button>
        <button class="btn btn-sm" data-move="down" data-i="${idx}">↓</button>
        <button class="btn btn-sm" data-del="${idx}">Supprimer</button>
      </div>`;
    wrap.appendChild(div);
  });
  wrap.querySelectorAll('input,textarea').forEach(el=>{
    el.addEventListener('input', e=>{
      markSaving();
      const i = +e.target.dataset.i, k = e.target.dataset.k; sec.items[i][k] = e.target.value; debounceSave();
    });
  });
  wrap.querySelectorAll('[data-del]').forEach(btn=> btn.addEventListener('click', e=>{ sec.items.splice(+btn.dataset.del,1); renderExp(); save(); }));
  wrap.querySelectorAll('[data-move]').forEach(btn=> btn.addEventListener('click', e=>{
    const i = +btn.dataset.i; const dir = btn.dataset.move==='up'?-1:1; const j = i+dir;
    if(j<0||j>=sec.items.length) return; [sec.items[i],sec.items[j]]=[sec.items[j],sec.items[i]]; renderExp(); save();
  }));
}

document.getElementById('addExp').addEventListener('click', ()=>{
  let sec = data.sections.find(s=>s.type==='experience'); if(!sec){ sec={type:'experience',items:[]}; data.sections.push(sec); }
  sec.items.push({title:'',subtitle:'',period:'',desc:''}); renderExp(); save();
});

// EDUCATION
function renderEdu(){
  data.sections = data.sections || [];
  let sec = data.sections.find(s=>s.type==='education');
  if(!sec){ sec = {type:'education', items:[]}; data.sections.push(sec); }
  const wrap = document.getElementById('eduList'); wrap.innerHTML='';
  (sec.items||[]).forEach((it,idx)=>{
    const div = document.createElement('div');
    div.className='item';
    div.innerHTML = `
      <div class="grid-3">
        <div>
          <label class="label">Diplôme</label>
          <input class="input" data-k="title" data-i="${idx}" value="${it.title||''}" placeholder="Ex: Licence Informatique">
        </div>
        <div>
          <label class="label">Établissement</label>
          <input class="input" data-k="subtitle" data-i="${idx}" value="${it.subtitle||''}" placeholder="Ex: Université X">
        </div>
        <div>
          <label class="label">Période</label>
          <input class="input" data-k="period" data-i="${idx}" value="${it.period||''}" placeholder="Ex: 2020 – 2023">
        </div>
      </div>
      <label class="label">Détails</label>
      <textarea data-k="desc" data-i="${idx}" placeholder="Principaux cours, mention, projets..."></textarea>
      <div class="item-actions">
        <button class="btn btn-sm" data-del="${idx}">Supprimer</button>
      </div>`;
    wrap.appendChild(div);
  });
  wrap.querySelectorAll('input,textarea').forEach(el=> el.addEventListener('input', e=>{
    markSaving();
    const i=+e.target.dataset.i, k=e.target.dataset.k; let sec=data.sections.find(s=>s.type==='education'); sec.items[i][k]=e.target.value; debounceSave();
  }));
  wrap.querySelectorAll('[data-del]').forEach(btn=> btn.addEventListener('click', e=>{ let sec=data.sections.find(s=>s.type==='education'); sec.items.splice(+btn.dataset.del,1); renderEdu(); save(); }));
}

document.getElementById('addEdu').addEventListener('click', ()=>{ let sec=data.sections.find(s=>s.type==='education'); if(!sec){sec={type:'education',items:[]}; data.sections.push(sec);} sec.items.push({title:'',subtitle:'',period:'',desc:''}); renderEdu(); save(); });

// SKILLS
function ensureSkillsSec(){ data.sections=data.sections||[]; let s=data.sections.find(x=>x.type==='skills'); if(!s){ s={type:'skills',items:[]}; data.sections.push(s);} return s; }
function renderSkills(){ const wrap=document.getElementById('skillList'); wrap.innerHTML=''; const s=ensureSkillsSec(); (s.items||[]).forEach((sk,idx)=>{
  const chip=document.createElement('span'); chip.className='chip'; chip.textContent=sk.title||sk; chip.setAttribute('data-idx', idx);
  chip.addEventListener('click',()=>{ s.items.splice(idx,1); renderSkills(); save(); }); wrap.appendChild(chip);
}); }

document.getElementById('addSkill').addEventListener('click', ()=>{ const input=document.getElementById('skillInput'); const v=input.value.trim(); if(!v) return; const s=ensureSkillsSec(); s.items.push({title:v}); input.value=''; renderSkills(); save(); });
 document.querySelectorAll('[data-skill]').forEach(ch=> ch.addEventListener('click',()=>{ const s=ensureSkillsSec(); const v=ch.getAttribute('data-skill'); if(!s.items.find(it=>(it.title||it)===v)){ s.items.push({title:v}); renderSkills(); save(); } }));

// LANGUAGES (select-only UX)
function ensureLangSec(){ data.sections=data.sections||[]; let s=data.sections.find(x=>x.type==='languages'); if(!s){ s={type:'languages',items:[]}; data.sections.push(s);} return s; }
function renderLang(){ const wrap=document.getElementById('langList'); wrap.innerHTML=''; const s=ensureLangSec(); (s.items||[]).forEach((lg,idx)=>{
  const row=document.createElement('div'); row.className='item';
  row.innerHTML = `
    <div class="grid-3">
      <div>
        <label class="label">Langue</label>
        <input class="input" data-k="name" data-i="${idx}" value="${lg.name||''}" list="langData">
      </div>
      <div>
        <label class="label">Niveau</label>
        <select class="select" data-k="level" data-i="${idx}">
          ${['A1 Débutant','A2 Élémentaire','B1 Intermédiaire','B2 Intermédiaire avancé','C1 Avancé','C2 Maîtrise','Natif / Bilingue'].map(l=>`<option ${l===(lg.level||'')?'selected':''}>${l}</option>`).join('')}
        </select>
      </div>
      <div class="item-actions" style="align-items:end;justify-content:flex-end">
        <button class="btn btn-sm" data-del="${idx}">Supprimer</button>
      </div>
    </div>`;
  wrap.appendChild(row);
  });
  wrap.querySelectorAll('input,select').forEach(el=> el.addEventListener('input', e=>{ const i=+e.target.dataset.i, k=e.target.dataset.k; const s=ensureLangSec(); s.items[i][k]=e.target.value; markSaving(); debounceSave(); }));
  wrap.querySelectorAll('[data-del]').forEach(btn=> btn.addEventListener('click', ()=>{ const s=ensureLangSec(); s.items.splice(+btn.dataset.del,1); renderLang(); save(); }));
}

document.getElementById('addLang').addEventListener('click', ()=>{
  const name = document.getElementById('langName').value.trim();
  const level= document.getElementById('langLevel').value.trim();
  if(!name || !level) return;
  const s = ensureLangSec();
  if(!s.items.find(it=> (it.name||'').toLowerCase()===name.toLowerCase())) s.items.push({name, level});
  document.getElementById('langName').value=''; document.getElementById('langLevel').value='';
  renderLang(); save();
});

// Template select
const templateSelect = document.getElementById('templateSelect');
templateSelect.addEventListener('change', e=>{
  const b = new URLSearchParams({cv_id: cvId, template_slug: e.target.value});
  fetch('api/select_template.php', {method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:b})
    .then(()=>{ refreshPreview(); })
});

// Progress (simple heuristique)
function updateProgress(){
  const hdr = data.header||{}; let score=0, total=6;
  ['full_name','title','email','phone','address'].forEach(k=>{ if((hdr[k]||'').trim()) score++; });
  const exp = (data.sections||[]).find(s=>s.type==='experience'); if(exp && exp.items && exp.items.length>0) score++;
  const pct = Math.round(score/total*100);
  document.getElementById('progressTxt').textContent = 'Complétude : '+pct+'%';
}

// Datalist langues
const datalist = document.createElement('datalist'); datalist.id='langData';
['Arabe','Français','Anglais','Espagnol','Allemand','Italien','Portugais','Chinois (Mandarin)','Japonais','Turc','Russe','Néerlandais','Hindi']
  .forEach(l=>{ const o=document.createElement('option'); o.value=l; datalist.appendChild(o); });
document.body.appendChild(datalist);

// Boot
renderExp(); renderEdu(); renderSkills(); renderLang(); updateProgress(); markSaved();
</script>
</body>
</html>