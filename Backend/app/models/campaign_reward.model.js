const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Campaign_Reward = sequelize.define("campaign_reward", {
      campaign_reward_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      campaign_id: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {         // Reward belongsTo Campaign 1:1
            model: 'campaigns',
            key: 'campaign_id'
        }  
      },
      type: {
        type: Sequelize.STRING(64)
      },
      reward_name: {
        type: Sequelize.STRING(64)
      },
      reward_description: {
        type: Sequelize.STRING(128)
      },
      amount: {
        type: Sequelize.FLOAT
      }
    });
    
    return Campaign_Reward;
  };