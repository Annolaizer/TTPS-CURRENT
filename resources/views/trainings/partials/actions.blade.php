<button class="btn btn-sm btn-custom-primary" onclick="viewTraining({{ $training->training_id }})">
    <i class="fas fa-eye"></i>
</button>
<button class="btn btn-sm btn-custom-primary" onclick="editTraining({{ $training->training_id }})">
    <i class="fas fa-edit"></i>
</button>
<button class="btn btn-sm btn-danger" onclick="deleteTraining({{ $training->training_id }})">
    <i class="fas fa-trash"></i>
</button>
