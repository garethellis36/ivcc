<?= $this->Form->create($match, [
    "class" => ($this->request->params['action'] == 'edit' ? 'edit-match' : 'add-match'),
    "novalidate" => true
]); ?>
<fieldset>
    <legend><?= __(ucfirst($this->request->params['action']) . ' Match') ?></legend>

    <div class="columns break">
        <div class="column one-fifth">
            <?php

            $date = null;
            if ($match->date instanceof Cake\i8n\Time) {
                $date = $match->date->format("Y-m-d H:i");
            } elseif ($match->date) {
                $date = date("Y-m-d H:i", strtotime($match->date));
            }

            echo $this->Form->input('date', [
                'class' => 'datetimepicker input-mini',
                'type' => 'text',
                'value' => $date
            ]);
            echo $this->Form->input('opposition', [
                'class' => 'input-mini'
            ]);
            ?>
        </div>
        <div class="column one-fifth">
            <?php
            echo $this->Form->input('venue', [
            'type' => 'select',
            'options' => [
            'A' => 'A',
            'H' => 'H'
            ],
            'class' => 'input-mini'
            ]);
            echo $this->Form->input('format_id', [
            'class' => 'input-mini'
            ]);
            ?>
        </div>

        <?php if ($this->request->params['action'] == 'edit'): ?>
            <div class="column one-fifth">
                <?php
                echo $this->Form->input("result", [
                    "class" => "input-mini resultSelect",
                    "type" => "select",
                    "empty" => "--"
                ]);

                echo $this->Form->input("result_more", [
                    "class" => "input-mini disableWithoutResult",
                    "label" => "by (e.g. 40 runs)"
                ]);
                ?>
            </div>

            <div class="column one-fifth">
                <?php
                echo $this->Form->input("ivcc_total", [
                    "class" => "input-mini disableWithoutResult",
                    'label' => "IVCC total",
                    "type" => "number"
                ]);

                echo $this->Form->input("ivcc_extras", [
                    "class" => "input-mini disableWithoutResult",
                    'label' => "IVCC extras"
                ]);

                echo $this->Form->input("ivcc_wickets", [
                    "class" => "input-mini disableWithoutResult",
                    'label' => "IVCC wickets"
                ]);

                echo $this->Form->input("ivcc_overs", [
                    "class" => "input-mini disableWithoutResult",
                    'label' => "IVCC overs"
                ]);

                echo $this->Form->input("ivcc_batted_first", [
                    "class" => "input-mini disableWithoutResult",
                    "label" => "IVCC batted first?",
                    "type" => "checkbox",
                    "checked" => (!isset($match->result) || $match->ivcc_batted_first == 1 ? true : false),
                    "required" => false
                ]);
                ?>

            </div>

            <div class="column one-fifth">
                <?php
                echo $this->Form->input("opposition_total", [
                    "class" => "input-mini disableWithoutResult",
                    "label" => "Opposition total"
                ]);

                echo $this->Form->input("opposition_wickets", [
                    "class" => "input-mini disableWithoutResult",
                    "label" => "Opposition wickets"
                ]);

                echo $this->Form->input("opposition_overs", [
                    "class" => "input-mini disableWithoutResult",
                    "label" => "Opposition overs"
                ]);

                ?>
            </div>
        <?php endif; ?>

    </div>

    <?php if ($this->request->params['action'] == 'edit'): ?>

        <div class="columns padding-bottom">
            <div class="column">
                <?= $this->Form->input("match_report", [
                    "class" => "huge"
                ]) ?>
            </div>
        </div>

        <div class="columns padding-bottom">
            <div class="column">
                <?= $this->Form->input("captain_id", [
                    "options" => $players,
                    "empty" => "Select..."
                ]) ?>
                <?= $this->Form->input("wicketkeeper_id", [
                    "options" => $players,
                    "empty" => "Select..."
                ]) ?>
            </div>
        </div>

        <div id="scorecardErrors" class="flash flash-error<?= (isset($scorecardErrors) ? "" : " hide") ?>">
            <h4>Please address the following errors in the scorecard:</h4>
            <ul class="no-bullets">
                <?php if (isset($scorecardErrors)): ?>
                    <?php foreach ($scorecardErrors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

        <table class="small-headings no-vertical-padding">

            <thead>

                <tr>
                    <th rowspan="2">Player</th>
                    <th colspan="4">Batting</th>
                    <th colspan="5">Bowling</th>
                </tr>
                <tr>
                    <th>DNB</th>
                    <th>Batting order #</th>
                    <th>Runs</th>
                    <th>Mode of dismissal</th>
                    <th>Overs</th>
                    <th>Maidens</th>
                    <th>Runs</th>
                    <th>Wickets</th>
                    <th>Bowling order #</th>
                    <th>Catches</th>
                </tr>

            </thead>

        <?php for ($i = 0; $i <= 10; $i++): ?>

        <tr id="playerRow<?= $i ?>">

            <?php

            echo $this->Form->input("matches_players." . $i . ".id", [
                "type" => "hidden"
            ]);

            $defaultOptions = [
                "label" => false,
                "data-rowId" => $i,
                "error" => false,
                "required" => false
            ];
            foreach ($playerRowFields as $fieldName => $options) {
                if (isset($options["default"]) && $options["default"] == "rowNumber") {
                    $options["default"] = $i+1;
                }
                echo "<td>";
                echo $this->Form->input("matches_players." . $i . "." . $fieldName,
                    array_merge($options, $defaultOptions)
                );
                echo "</td>";
            }
            ?>

        </tr>

        <?php endfor; ?>

        </table>

    <?php endif; ?>

</fieldset>

<?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']) ?>
<?= $this->Form->end() ?>