<?php

/**
 * @copyright Copyright &copy; Gogodigital Srls
 * @company Gogodigital Srls - Wide ICT Solutions
 * @website http://www.gogodigital.it
 * @github https://github.com/cinghie/yii2-paypal
 * @license BSD-3-Clause
 * @package yii2-paypal
 * @version 0.2.3
 */

namespace cinghie\paypal\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use cinghie\paypal\models\Payments;

/**
 * PaymentsSearch represents the model behind the search form of `cinghie\paypal\models\Payments`.
 */
class PaymentsSearch extends Payments
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'user_id', 'created_by'], 'integer'],
            [['payment_id', 'client_token', 'payment_method', 'payment_state', 'created'], 'safe'],
            [['total_paid'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Payments::find();
	    $query->joinWith('createdBy');

	    $dataProvider = new ActiveDataProvider([
		    'query' => $query,
		    'sort' => [
			    'defaultOrder' => [
				    'created' => SORT_DESC
			    ],
		    ],
	    ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'total_paid' => $this->total_paid,
            'created_by' => $this->created_by
        ]);

        $query->andFilterWhere(['like', 'payment_id', $this->payment_id])
            ->andFilterWhere(['like', 'client_token', $this->client_token])
            ->andFilterWhere(['like', 'payment_method', $this->payment_method])
            ->andFilterWhere(['like', 'created', $this->created])
            ->andFilterWhere(['like', 'payment_state', $this->payment_state]);

        return $dataProvider;
    }
}
