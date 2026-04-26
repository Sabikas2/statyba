<?php
declare(strict_types=1);

if (file_exists(__DIR__ . '/config.php')) { echo 'Jau įdiegta. Ištrinkite config.php jei norite perinstaliuoti.'; exit; }

$errors=[]; $ok='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $cfg = require __DIR__ . '/config.sample.php';
    $cfg['base_url'] = trim($_POST['base_url'] ?? '');
    $cfg['db']['host'] = trim($_POST['db_host']);
    $cfg['db']['port'] = trim($_POST['db_port']);
    $cfg['db']['name'] = trim($_POST['db_name']);
    $cfg['db']['user'] = trim($_POST['db_user']);
    $cfg['db']['pass'] = trim($_POST['db_pass']);

    try {
        $pdo = new PDO(sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',$cfg['db']['host'],$cfg['db']['port'],$cfg['db']['name']),$cfg['db']['user'],$cfg['db']['pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
        $sql = file_get_contents(__DIR__ . '/storage/schema.sql');
        $pdo->exec($sql);

        $hash=password_hash('admin123', PASSWORD_DEFAULT);
        $stmt=$pdo->prepare('INSERT INTO users (name,email,phone,password_hash,role,status,created_at,updated_at) VALUES (?,?,?,?,?,?,NOW(),NOW()) ON DUPLICATE KEY UPDATE email=email');
        $stmt->execute(['Admin','admin@example.com','',$hash,'admin','active']);

        if (!empty($_POST['seed_demo'])) {
            $pdo->exec("INSERT IGNORE INTO users (id,name,email,phone,password_hash,role,status,created_at,updated_at) VALUES
            (2,'Client Demo','client@example.com','', '" . password_hash('client123', PASSWORD_DEFAULT) . "','client','active',NOW(),NOW()),
            (3,'Contractor Demo','contractor@example.com','', '" . password_hash('contractor123', PASSWORD_DEFAULT) . "','contractor','active',NOW(),NOW())");
            $pdo->exec("INSERT IGNORE INTO project_categories (id,name,slug) VALUES (1,'Pamatai','pamatai'),(2,'Mūras','muras'),(3,'Stogas','stogas'),(4,'Langai','langai'),(5,'Fasadas','fasadas'),(6,'Trinkelės','trinkeles'),(7,'Vidaus apdaila','vidaus-apdaila'),(8,'Inžinerija','inzinerija')");
            $pdo->exec("INSERT IGNORE INTO contractor_profiles (id,user_id,company_name,contact_person,email,phone,city,region,description,categories,service_radius_km,consent_to_contact,source,rating_avg,rating_count,status,created_at,updated_at) VALUES
            (1,3,'Demo Statyba','Jonas','contractor@example.com','','Vilnius','Vilniaus','Demo','[\"1\",\"3\",\"5\"]',40,1,'self_registered',4.8,10,'approved',NOW(),NOW()),
            (2,NULL,'Mūro meistrai','Petras','muras@example.com','','Kaunas','Kauno','Mūras','[\"2\"]',30,1,'manual',4.2,6,'approved',NOW(),NOW()),
            (3,NULL,'Stogo profai','Tomas','stogas@example.com','','Klaipėda','Klaipėdos','Stogai','[\"3\"]',50,1,'manual',4.6,8,'approved',NOW(),NOW()),
            (4,NULL,'Langų pasaulis','Asta','langai@example.com','','Vilnius','Vilniaus','Langai','[\"4\"]',25,1,'manual',4.1,5,'approved',NOW(),NOW()),
            (5,NULL,'Fasadai LT','Mantas','fasadas@example.com','','Kaunas','Kauno','Fasadai','[\"5\"]',35,1,'manual',4.3,7,'approved',NOW(),NOW())");
            $pdo->exec("INSERT IGNORE INTO projects (id,client_id,title,category_id,city,region,address_optional,budget_min,budget_max,desired_start_date,description,status,created_at,updated_at) VALUES
            (1,2,'Demo namo stogas',3,'Vilnius','Vilniaus','',12000,18000,NULL,'Reikia pakeisti stogą', 'open', NOW(),NOW())");
            $pdo->exec("INSERT IGNORE INTO bids (id,project_id,contractor_profile_id,price,price_type,duration_days,message,includes_materials,warranty_months,status,created_at,updated_at) VALUES
            (1,1,1,15000,'fixed',20,'Pilnas darbas',1,36,'submitted',NOW(),NOW()),
            (2,1,3,14200,'estimate',18,'Su medžiagomis',1,24,'submitted',NOW(),NOW())");
        }

        $configExport = "<?php\nreturn " . var_export($cfg, true) . ";\n";
        file_put_contents(__DIR__ . '/config.php', $configExport);
        $ok='Diegimas sėkmingas! Prisijunkite: admin@example.com / admin123';
    } catch (Throwable $e) {
        $errors[] = $e->getMessage();
    }
}
?><!doctype html><html><head><meta charset="utf-8"><title>BuildMatch install</title><link rel="stylesheet" href="assets/style.css"></head><body><main class="container"><h1>BuildMatch AI install</h1>
<?php foreach($errors as $er): ?><div class="flash err"><?= htmlspecialchars($er) ?></div><?php endforeach; ?>
<?php if($ok): ?><div class="flash ok"><?= htmlspecialchars($ok) ?></div><p><a class="btn" href="index.php">Eiti į sistemą</a></p><?php endif; ?>
<form method="post"><input name="base_url" placeholder="Base URL (pvz https://domenas.lt)" required><input name="db_host" placeholder="DB host" value="localhost" required><input name="db_port" placeholder="DB port" value="3306" required><input name="db_name" placeholder="DB name" required><input name="db_user" placeholder="DB user" required><input name="db_pass" placeholder="DB pass"><label><input type="checkbox" name="seed_demo" value="1"> Seed demo data</label><button class="btn">Install</button></form></main></body></html>
