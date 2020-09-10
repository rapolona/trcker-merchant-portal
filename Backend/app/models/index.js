const dbConfig = require("../../config/db.config.js");

const Sequelize = require("sequelize");

const sequelize = new Sequelize(dbConfig.DB,dbConfig.USER, dbConfig.PASSWORD, {
    host: dbConfig.HOST,
    dialect: dbConfig.dialect,
    operatorAliases: false,

    pool:{
        max: dbConfig.max,
        min: dbConfig.min,
        acquire:dbConfig.acquire,
        idle: dbConfig.pool.idle
    }
});

const db = {}

db.Sequelize = Sequelize;
db.sequelize = sequelize;

db.admins = require("./admin.model.js")(sequelize, Sequelize);
db.admindetails = require("./admindetail.model.js")(sequelize, Sequelize);
db.adminsessions = require("./adminsession.model.js")(sequelize, Sequelize);

db.users = require("./user.model.js")(sequelize, Sequelize);
db.userdetails = require("./userdetail.model.js")(sequelize, Sequelize);

//Merchant Related Tables
db.merchants = require("./merchant.model.js")(sequelize, Sequelize);
db.branches = require("./branch.model.js")(sequelize, Sequelize);
db.products = require("./product.model.js")(sequelize, Sequelize);

//Campaign Related Tables
db.campaigns = require("./campaign.model.js")(sequelize, Sequelize);
db.campaign_branch_associations = require("./campaign_branch_association.model.js")(sequelize, Sequelize);
db.task_classifications = require("./task_classification.model.js")(sequelize, Sequelize);
db.tasks = require("./task.model.js")(sequelize, Sequelize);
db.task_questions = require("./task_question.model.js")(sequelize, Sequelize);
db.campaign_rewards = require("./campaign_reward.model.js")(sequelize, Sequelize);
db.task_questions = require("./task_question.model.js")(sequelize, Sequelize);
db.task_question_choices = require("./task_question_choices.model.js")(sequelize, Sequelize);

db.task_tickets = require("./task_ticket.model.js")(sequelize, Sequelize);
db.task_details = require("./task_detail.model.js")(sequelize, Sequelize);

db.admins.hasOne(db.admindetails, { foreignKey: "admin_id", as:"adminDetails"});
db.admins.hasOne(db.adminsessions, {foreignKey: "admin_id", as:"adminSessions"});
db.merchants.hasMany(db.admins, {foreignKey: "merchant_id", as:"merchantAdmins"})

db.users.hasOne(db.userdetails, { foreignKey: "user_id", as:"userDetails"});

//Merchant Related Associations
db.merchants.hasMany(db.branches, {foreignKey:'merchant_id'});
db.branches.belongsTo(db.merchants, {foreignKey: "merchant_id"});

db.merchants.hasMany(db.products, {foreignKey:'merchant_id'});
db.products.belongsTo(db.merchants, {foreignKey: "merchant_id"});

//Campaign Related Associations
db.campaigns.hasMany(db.campaign_branch_associations, {foreignKey:'campaign_id'},{ onDelete: 'cascade' });
db.campaign_branch_associations.belongsTo(db.campaigns, {foreignKey: "campaign_id"});

db.branches.hasMany(db.campaign_branch_associations, {foreignKey:'branch_id'}, { onDelete: 'cascade' });
db.campaign_branch_associations.belongsTo(db.branches, {foreignKey: "branch_id"});

db.campaigns.belongsToMany(db.branches,{through:db.campaign_branch_associations, foreignKey:'campaign_id'})
db.branches.belongsToMany(db.campaigns,{through:db.campaign_branch_associations,foreignKey:'branch_id'})

// db.campaigns.hasMany(db.task_questions, {foreignKey:'campaign_id'},{ onDelete: 'cascade' });
// db.task_questions.belongsTo(db.campaigns, {foreignKey: "campaign_id"});

db.tasks.hasMany(db.task_questions, {foreignKey:'task_action_id'},{ onDelete: 'cascade' });
db.task_questions.belongsTo(db.tasks, {foreignKey: "task_action_id"});
db.task_questions.hasMany(db.task_question_choices, {foreignKey: "task_question_id"})

db.campaigns.hasOne(db.campaign_rewards, {foreignKey:'campaign_id'},{ onDelete: 'cascade' });
db.campaign_rewards.belongsTo(db.campaigns, {foreignKey: "campaign_id"});

db.task_classifications.hasMany(db.tasks, {foreignKey:'task_action_classification_id'});
db.tasks.belongsTo(db.task_classifications, {foreignKey: "task_action_classification_id"});

db.task_tickets.belongsTo(db.campaigns,{foreignKey:'campaign_id'});
db.task_tickets.belongsTo(db.userdetails, {foreignKey:'user_id', sourceKey: 'user_id', targetKey: 'user_id'});
db.task_tickets.hasMany(db.task_details, {foreignKey:'task_ticket_id'});

db.task_questions.hasMany(db.task_details, {foreignKey: 'task_question_id'});
db.task_details.belongsTo(db.task_questions, {foreignKey:'task_question_id'})

module.exports = db