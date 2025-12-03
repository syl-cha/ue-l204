# ue-l204
Mini-projet de l'UE-L204

## Base de données

Voici une modélisation de la base de données pour ce mini-projet. IL s'agit d'une BDD à destination d'une université
qui permettrait de gérer des étudiants et des enseignants autour de cours.

```mermaid
erDiagram
    %% ---------------------------------------------------------
    %% Entité Parente : Utilisateur (Héritage)
    %% ---------------------------------------------------------
    UTILISATEUR {
        INT id PK
        VARCHAR(255) login
        VARCHAR(255) mot_de_passe
        VARCHAR(100) nom
        VARCHAR(100) prenom
        VARCHAR(255) email
        ENUM role "enseignant, etudiant, admin"
        TIMESTAMP date_creation
        BOOLEAN actif
    }

    %% ---------------------------------------------------------
    %% Entités Enfants (Spécialisation)
    %% ---------------------------------------------------------
    ENSEIGNANT {
        INT id PK
        INT utilisateur_id FK
        VARCHAR(50) bureau
        VARCHAR(50) telephone
        VARCHAR(255) specialite
        ENUM statut "titulaire, vacataire, contractuel"
    }

    ETUDIANT {
        INT id PK
        INT utilisateur_id FK
        VARCHAR(20) numero_etudiant
        ENUM niveau "L1, L2, L3, M1, M2"
        DATE date_inscription
    }

    %% ---------------------------------------------------------
    %% Entité : Cours
    %% ---------------------------------------------------------
    COURS {
        INT id PK
        VARCHAR(20) code
        VARCHAR(255) nom
        INT credits
        TEXT description
        INT capacite_max
        BOOLEAN actif
    }

    %% ---------------------------------------------------------
    %% Tables de Liaison (Associations N:N)
    %% ---------------------------------------------------------
    INSCRIPTION {
        INT id PK
        INT etudiant_id FK
        INT cours_id FK
        TIMESTAMP date_inscription
        DECIMAL(4,2) note
        ENUM statut "en_attente, valide, refuse, abandonne"
        BOOLEAN valide
    }

    ENSEIGNE {
        INT id PK
        INT enseignant_id FK
        INT cours_id FK
        VARCHAR(9) annee_universitaire
        BOOLEAN responsable
    }

    PREREQUIS {
        INT cours_id FK
        INT prerequis_cours_id FK
    }

    %% ---------------------------------------------------------
    %% Relations
    %% ---------------------------------------------------------
    
    %% Héritage (1 utilisateur peut être 0 ou 1 étudiant/enseignant)
    UTILISATEUR ||--o| ENSEIGNANT : "est spécialisé en"
    UTILISATEUR ||--o| ETUDIANT : "est spécialisé en"

    %% Inscriptions aux cours
    ETUDIANT ||--o{ INSCRIPTION : "s'inscrit"
    COURS ||--o{ INSCRIPTION : "est suivi par"

    %% Gestion des enseignements
    ENSEIGNANT ||--o{ ENSEIGNE : "enseigne"
    COURS ||--o{ ENSEIGNE : "est enseigné par"

    %% Auto-jointure pour les prérequis
    COURS ||--o{ PREREQUIS : "requiert"
    COURS ||--o{ PREREQUIS : "est requis pour"
    ```
