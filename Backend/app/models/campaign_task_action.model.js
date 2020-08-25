const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Campaign_Task_Action = sequelize.define("campaign_task_action", {
      campaign_task_action_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      title: {
        type: Sequelize.STRING(64),
        allowNull: false
      },
      description: {
        type: Sequelize.STRING(255)
      },
      required_inputs: {
        type: Sequelize.STRING(64)
      },
      benefits: {
        type: Sequelize.STRING(64)
      },
      campaign_id: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {         // Product belongsTo Merchant 1:1
          model: 'campaigns',
          key: 'campaign_id'
        }  
      },
      task_action_id: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {         // Product belongsTo Merchant 1:1
          model: 'task_actions',
          key: 'task_action_id'
        }  
      }
    });
    return Campaign_Task_Action;
  };