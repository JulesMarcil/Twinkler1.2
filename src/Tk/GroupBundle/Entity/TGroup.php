<?php

namespace Tk\GroupBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TGroup
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Tk\GroupBundle\Entity\TGroupRepository")
 */
class TGroup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    protected $date;

    /**
     * @ORM\ManyToOne(targetEntity="Tk\GroupBundle\Entity\Currency", cascade={"persist"})
     */
    protected $currency;

    /**
     * @var string
     *
     * @ORM\Column(name="invitationToken", type="string", length=255)
     */
    protected $invitationToken;

    /**
     * @ORM\OneToMany(targetEntity="Tk\UserBundle\Entity\Member", mappedBy="tgroup", cascade={"persist"})
     */
    protected $members;

    /**
     * @ORM\OneToMany(targetEntity="Tk\ExpenseBundle\Entity\Expense", mappedBy="group", cascade={"persist"})
     * @ORM\OrderBy({"date" = "DESC"})
     */
    protected $expenses;

    /**
     * @ORM\OneToMany(targetEntity="Tk\ListBundle\Entity\Lists", mappedBy="group", cascade={"persist"})
     */
    protected $lists;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return TGroup
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return TGroup
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set invitationToken
     *
     * @param string $invitationToken
     * @return TGroup
     */
    public function setInvitationToken($invitationToken)
    {
        $this->invitationToken = $invitationToken;

        return $this;
    }

    /**
     * Get invitationToken
     *
     * @return string 
     */
    public function getInvitationToken()
    {
        return $this->invitationToken;
    }

    /**
     * Generate invitationToken
     *
     * @return string 
     */
    public function generateInvitationToken()
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < 40; $i++) {
            $key .= $keys[array_rand($keys)];
        }

    return $key;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
        $this->expenses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add expenses
     *
     * @param \Tk\ExpenseBundle\Entity\Expense $expenses
     * @return TGroup
     */
    public function addExpense(\Tk\ExpenseBundle\Entity\Expense $expenses)
    {
        $this->expenses[] = $expenses;

        return $this;
    }

    /**
     * Remove expenses
     *
     * @param \Tk\ExpenseBundle\Entity\Expense $expenses
     */
    public function removeExpense(\Tk\ExpenseBundle\Entity\Expense $expenses)
    {
        $this->expenses->removeElement($expenses);
    }

    /**
     * Get expenses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExpenses()
    {
        return $this->expenses;
    }

    /**
     * Get total paid
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTotalPaid()
    {
        $all_expenses = $this->expenses;

        $sum = 0;
        foreach($all_expenses as $expense){
            $sum += $expense->getAmount();
        }
        return $sum;
    }    

    /**
     * Add members
     *
     * @param \Tk\UserBundle\Entity\Member $members
     * @return TGroup
     */
    public function addMember(\Tk\UserBundle\Entity\Member $members)
    {
        $this->members[] = $members;

        return $this;
    }

    /**
     * Remove members
     *
     * @param \Tk\UserBundle\Entity\Member $members
     */
    public function removeMember(\Tk\UserBundle\Entity\Member $members)
    {
        $this->members->removeElement($members);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembers()
    {
        $all_members = $this->members;
        $active_members = new \Doctrine\Common\Collections\ArrayCollection();
        foreach($all_members as $member){
            if($member->getActive() == true){
                $active_members->add($member);
            }
        }
        return $active_members;
    }

    /**
     * Get array_members
     *
     * @return array
     */
    public function getArrayMembers()
    {
        $collection_members = $this->getMembers();

        $array_members = array();
        foreach ($collection_members as $member) {
            $array_members[] = $member->getName();
        }

        return $array_members;
    }

    /**
     * Get array_balances
     *
     * @return array
     */
    public function getArrayBalances()
    {
        $collection_members = $this->getMembers();

        $array_balances = array();
        foreach ($collection_members as $member) {
            $array_balances[] = $member->getBalance();
        }

        return $array_balances;
    }

    /**
     * Set currency
     *
     * @param \Tk\GroupBundle\Entity\Currency $currency
     * @return TGroup
     */
    public function setCurrency(\Tk\GroupBundle\Entity\Currency $currency = null)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return \Tk\GroupBundle\Entity\Currency 
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Add lists
     *
     * @param \Tk\ListBundle\Entity\Lists $lists
     * @return TGroup
     */
    public function addList(\Tk\ListBundle\Entity\Lists $lists)
    {
        $this->lists[] = $lists;

        return $this;
    }

    /**
     * Remove lists
     *
     * @param \Tk\ListBundle\Entity\Lists $lists
     */
    public function removeList(\Tk\ListBundle\Entity\Lists $lists)
    {
        $this->lists->removeElement($lists);
    }

    /**
     * Get lists
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLists()
    {
        return $this->lists;
    }
}
