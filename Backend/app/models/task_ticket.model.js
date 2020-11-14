const {DataTypes} = require("sequelize")
module.exports = (sequelize, Sequelize) => {
    const Task_Ticket = sequelize.define("task_ticket", {
      task_ticket_id:{
        type: DataTypes.UUID,
        primaryKey: true,
        defaultValue: Sequelize.UUIDV4,
        unique:true
      },
      device_id: {
        type: Sequelize.STRING(255),
        allowNull: false
      },
      approval_status: {
        type: Sequelize.STRING(64)
      },
      campaign_id:{
        type: DataTypes.UUID,
        allowNull: false,
        references: {         // Product belongsTo Merchant 1:1
          model: 'campaigns',
          key: 'campaign_id'
        }  
      },
      user_id:{
        type: DataTypes.UUID,
        allowNull: false,
        references:{
          model: 'users',
          key:'user_id'
        }
      },
      branch_id:{
        type:DataTypes.UUID,
        allowNull:false,
        references:{
          model:'branches',
          key:'branch_id'
        }
      },
      task_classification_id: {
        type: DataTypes.UUID,
        allowNull: false,
        references: {         // Product belongsTo Merchant 1:1
          model: 'task_classifications',
          key: 'task_classification_id'
        }  
      }
    });
    return Task_Ticket;
  };