<?php  
/**
 * Class for handling Proposal-related operations.
 * Inherits CRUD methods from the Database class.
 */
class Proposal extends Database {

    /**
     * Inserts a new Proposal using an associative array.
     * @param array $data The proposal data (user_id, description, image, min_price, max_price)
     * @return bool True on success, false on failure
     */
    public function insertProposal($data) {
        $sql = "INSERT INTO Proposals (user_id, description, image, min_price, max_price, date_added)
                VALUES (?, ?, ?, ?, ?, NOW())";
        return $this->executeNonQuery($sql, [
            $data['user_id'],
            $data['description'],
            $data['image'],
            $data['min_price'],
            $data['max_price']
        ]);
    }

    public function createProposal($user_id, $description, $image, $min_price, $max_price) {
        $sql = "INSERT INTO Proposals (user_id, description, image, min_price, max_price)
                VALUES (?, ?, ?, ?, ?)";
        return $this->executeNonQuery($sql, [$user_id, $description, $image, $min_price, $max_price]);
    }

    public function getProposals($id = null) {
        if ($id) {
            $sql = "SELECT * FROM Proposals JOIN fiverr_clone_users ON Proposals.user_id = fiverr_clone_users.user_id WHERE Proposal_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT Proposals.*, fiverr_clone_users.*, 
                Proposals.date_added AS proposals_date_added
                FROM Proposals JOIN fiverr_clone_users ON 
                Proposals.user_id = fiverr_clone_users.user_id
                ORDER BY Proposals.date_added DESC";
        return $this->executeQuery($sql);
    }

    public function getProposalsByUserID($user_id) {
        $sql = "SELECT Proposals.*, fiverr_clone_users.*, 
                Proposals.date_added AS proposals_date_added
                FROM Proposals JOIN fiverr_clone_users ON 
                Proposals.user_id = fiverr_clone_users.user_id
                WHERE Proposals.user_id = ?
                ORDER BY Proposals.date_added DESC";
        return $this->executeQuery($sql, [$user_id]);
    }

    public function updateProposal($description, $min_price, $max_price, $proposal_id, $image = "") {
        if (!empty($image)) {
            $sql = "UPDATE Proposals SET description = ?, image = ?, min_price = ?, max_price = ? WHERE Proposal_id = ?";
            return $this->executeNonQuery($sql, [$description, $image, $min_price, $max_price, $proposal_id]);
        } else {
            $sql = "UPDATE Proposals SET description = ?, min_price = ?, max_price = ? WHERE Proposal_id = ?";
            return $this->executeNonQuery($sql, [$description, $min_price, $max_price, $proposal_id]);
        }
    }

    public function addViewCount($proposal_id) {
        $sql = "UPDATE Proposals SET view_count = view_count + 1 WHERE Proposal_id = ?";
        return $this->executeNonQuery($sql, [$proposal_id]);
    }

    public function deleteProposal($id) {
        $sql = "DELETE FROM Proposals WHERE Proposal_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}
?>
