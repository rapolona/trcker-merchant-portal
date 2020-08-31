const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Campaign = sequelize.define("campaign", {
      campaign_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      merchant_id: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {         // User belongsTo Company 1:1rs
            model: 'merchants',
            key: 'merchant_id'
        }  
      },
      campaign_name: {
        type: Sequelize.STRING(64)
      },
      campaign_description: {
        type: Sequelize.STRING(255)
      },
      start_date: {
        type: Sequelize.DATE
      },
      end_date: {
        type: Sequelize.DATE
      },
      audience_age_min: {
        type: Sequelize.INTEGER
      },
      audience_age_max: {
        type: Sequelize.INTEGER
      },
      audience_gender: {
        type: Sequelize.STRING(64)
      },
      allowed_account_level: {
        type: Sequelize.STRING(64)
      },
      budget: {
        type: Sequelize.FLOAT
      },
      super_shoppers: {
        type: Sequelize.BOOLEAN
      },
      allow_everyone: {
        type: Sequelize.BOOLEAN
      },
      status: {
        type: Sequelize.BOOLEAN
      },
      task_type: {
        type: Sequelize.STRING(64)
      },
    });
    
    return Campaign;
  };