# devsecops-infra-automation
Automated deployment of a secure campus IT infrastructure. Features strict network segmentation (VLANs), automated disaster recovery (Cron/NFS backups), and centralized Rsyslog monitoring managed via Ansible.

---

## 📁 Structure du projet

- `group_vars/` : variables partagées entre les hôtes
- `host_vars/` : variables spécifiques à un hôte
- `inventory/production.yml` : fichier d’inventaire pour l’environnement de production.
- `roles/` : dossier contenant les rôles Ansible, c’est-à-dire des unités réutilisables de configuration.
- `ansible.cfg` : fichier pour définir les paramètres par défaut du comportement d’Ansible
- `playbooks.yml` : Contient tous les playbooks

---

## 🚀 Exécution des playbooks
Pour info si un playbook fait appel à un groupe d'host qui possede un fichier de var chiffré, il va falloir mettre le paramètre "--ask-vault-pass"
```bash
ansible-playbook playbooks/bootstrap.yml --extra-vars "ansible_user=debian" -kK --ask-vault-pass
ansible-playbook playbooks/users_management.yml --ask-vault-pass
ansible-playbook playbooks/web_intranet.yml --ask-vault-pass

--ask-vault-pass = mdp compte root
```

**Modification depuis le terminal sans déchiffrer le fichier** des secrets des playbooks via **vim**
```bash
ansible-vault edit production/group_vars/galeraclusterProd.yml
```

**Déchiffrement complet du fichier** des secrets des playbooks
```bash
ansible-vault decrypt production/group_vars/galeraclusterProd.yml
```
**Chiffrement** des secrets des playbooks
```bash
ansible-vault encrypt production/group_vars/galeraclusterProd.yml
```


Création d'un role dans le dossier /roles
```bash
ansible-galaxy role init roles/nom_role
```

## ✏️ Les roles
### 📁 Structure du dossier `roles/`
- `roles/` : répertoire principal contenant les rôles Ansible (unités réutilisables de configuration et d'automatisation).  
  - `<nom_du_rôle>/` : chaque rôle est stocké dans un sous-dossier distinct.  
    - `defaults/` : variables par défaut avec priorité la plus faible (`main.yml`).  
    - `files/` : fichiers statiques à copier tels quels sur les hôtes distants.  
    - `handlers/` : actions déclenchées par des notifications (ex. : redémarrage de service).  
    - `meta/` : métadonnées du rôle (dépendances, auteur, licence, etc.).  
    - `tasks/` : contient la liste des tâches principales du rôle (`main.yml` est exécuté par défaut).  
    - `templates/` : modèles de fichiers Jinja2 (`.j2`) à personnaliser avec des variables.  
    - `tests/` : fichiers de test pour vérifier le bon fonctionnement du rôle (inventory et playbook de test).  
    - `vars/` : variables spécifiques au rôle avec priorité plus élevée que `defaults/` (`main.yml`).  
    - `README.md` : documentation spécifique au rôle, expliquant son usage, ses variables et ses prérequis.  


```bash
ssh-keygen -t ecdsa -b 521 -f ~/.ssh/ansible_key
```
