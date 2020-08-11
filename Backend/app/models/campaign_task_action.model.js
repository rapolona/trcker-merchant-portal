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
        type: Sequelize.STRING,
        allowNull: false
      },
      description: {
        type: Sequelize.STRING
      },
      required_inputs: {
        type: Sequelize.STRING
      },
      benefits: {
        type: Sequelize.STRING
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