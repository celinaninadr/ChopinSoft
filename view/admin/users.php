<h1>Administration - Utilisateurs</h1>

<div class="actions">
  <a class="btn" href="/ChopinSoft/index.php?route=admin/worlds">CRUD mondes</a>
  <a class="btn" href="/ChopinSoft/index.php?route=admin/avatars">CRUD avatars</a>
</div>

<h2>Ajouter un utilisateur</h2>
<form class="card" method="post">
  <input type="hidden" name="action" value="create">
  <div class="grid2">
    <div>
      <label>Username</label>
      <input name="username" required>
    </div>
    <div>
      <label>Password</label>
      <input type="password" name="password" required>
    </div>
  </div>
  <div class="grid2">
    <div>
      <label>Role</label>
      <select name="userRole" required>
        <option value="JOUEUR">JOUEUR</option>
        <option value="ADMIN">ADMIN</option>
      </select>
    </div>
    <div></div>
  </div>

  <div class="grid2">
    <div>
      <label>Avatar</label>
      <select name="idAvatar" required>
        <option value="">-- choisir --</option>
        <?php foreach ($avatars as $a): ?>
          <option value="<?php echo (int)$a['idAvatar']; ?>"><?php echo htmlspecialchars($a['nameAvatar']); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label>Monde</label>
      <select name="idWorld" required>
        <option value="">-- choisir --</option>
        <?php foreach ($worlds as $w): ?>
          <option value="<?php echo (int)$w['idWorld']; ?>"><?php echo htmlspecialchars($w['nameWorld']); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div class="actions">
    <button class="btn" type="submit">Créer</button>
  </div>
</form>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Role</th>
      <th>Avatar</th>
      <th>Monde</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?php echo (int)$u['idUser']; ?></td>
        <td>
          <form method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="idUser" value="<?php echo (int)$u['idUser']; ?>">
            <input name="username" value="<?php echo htmlspecialchars($u['username']); ?>" required>
        </td>
        <td>
            <select name="userRole" required>
              <option value="JOUEUR" <?php echo ($u['userRole'] === 'JOUEUR') ? 'selected' : ''; ?>>JOUEUR</option>
              <option value="ADMIN" <?php echo ($u['userRole'] === 'ADMIN') ? 'selected' : ''; ?>>ADMIN</option>
            </select>
        </td>
        <td>
            <select name="idAvatar" required>
              <?php foreach ($avatars as $a): ?>
                <option value="<?php echo (int)$a['idAvatar']; ?>" <?php echo ((int)$a['idAvatar'] === (int)$u['idAvatar']) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($a['nameAvatar']); ?>
                </option>
              <?php endforeach; ?>
            </select>
        </td>
        <td>
            <select name="idWorld" required>
              <?php foreach ($worlds as $w): ?>
                <option value="<?php echo (int)$w['idWorld']; ?>" <?php echo ((int)$w['idWorld'] === (int)$u['idWorld']) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($w['nameWorld']); ?>
                </option>
              <?php endforeach; ?>
            </select>
        </td>
        <td>
            <label class="small">Nouveau mdp (optionnel)</label>
            <input type="password" name="newPassword" placeholder="laisser vide">
            <div class="actions">
              <button class="btn" type="submit">Modifier</button>
          </form>

          <form method="post" onsubmit="return confirm('Supprimer ?')">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="idUser" value="<?php echo (int)$u['idUser']; ?>">
            <button class="btn danger" type="submit">Supprimer</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
