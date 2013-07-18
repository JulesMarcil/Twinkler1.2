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
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255, nullable=true)
     */
    protected $country;

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
     * @ORM\OneToMany(targetEntity="Tk\ListBundle\Entity\Todo", mappedBy="group", cascade={"persist"})
     * @ORM\OrderBy({"date" = "DESC"})
     */
    protected $todos;

    /**
     * @ORM\OneToMany(targetEntity="Tk\ListBundle\Entity\ShoppingItem", mappedBy="group", cascade={"persist"})
     * @ORM\OrderBy({"addedDate" = "DESC"})
     */
    protected $shoppingItems;

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
     * Set city
     *
     * @param string $city
     * @return TGroup
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return TGroup
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string 
     */
    public function getCountry()
    {
        return $this->country;
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
        $this->todos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->shoppingItems = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add todos
     *
     * @param \Tk\ListBundle\Entity\Todo $todos
     * @return TGroup
     */
    public function addTodo(\Tk\ListBundle\Entity\Todo $todos)
    {
        $this->todos[] = $todos;

        return $this;
    }

    /**
     * Remove todos
     *
     * @param \Tk\ListBundle\Entity\Todo $todos
     */
    public function removeTodo(\Tk\ListBundle\Entity\Todo $todos)
    {
        $this->todos->removeElement($todos);
    }

    /**
     * Get todos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTodos()
    {
        return $this->todos;
    }

    /**
     * Get Active todos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActiveTodos()
    {
        $all_todos = $this->todos;
        $active_todos = new \Doctrine\Common\Collections\ArrayCollection();

        foreach($all_todos as $todo){
            if($todo->getActive() == 1){
                $active_todos->add($todo);
            }else{}
        }
        return $active_todos;
    }

    /**
     * Get Inactive todos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInactiveTodos()
    {
        $all_todos = $this->todos;
        $inactive_todos = new \Doctrine\Common\Collections\ArrayCollection();

        foreach($all_todos as $todo){
            if($todo->getActive() == 0){
                $inactive_todos->add($todo);
            }else{}
        }
        return $inactive_todos;
    }

    /**
     * Add shoppingItems
     *
     * @param \Tk\ListBundle\Entity\ShoppingItem $shoppingItems
     * @return TGroup
     */
    public function addShoppingItem(\Tk\ListBundle\Entity\ShoppingItem $shoppingItems)
    {
        $this->shoppingItems[] = $shoppingItems;

        return $this;
    }

    /**
     * Remove shoppingItems
     *
     * @param \Tk\ListBundle\Entity\ShoppingItem $shoppingItems
     */
    public function removeShoppingItem(\Tk\ListBundle\Entity\ShoppingItem $shoppingItems)
    {
        $this->shoppingItems->removeElement($shoppingItems);
    }

    /**
     * Get shoppingItems
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getShoppingItems()
    {
        return $this->shoppingItems;
    }

    /**
     * Get Active shoppingItems
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActiveShoppingItems()
    {
        $all_items = $this->shoppingItems;
        $active_items = new \Doctrine\Common\Collections\ArrayCollection();

        foreach($all_items as $item){
            if($item->getActive() == 1){
                $active_items->add($item);
            }else{}
        }
        return $active_items;
    }

    /**
     * Get Inactive shoppingItems
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInactiveShoppingItems()
    {
        $all_items = $this->shoppingItems;
        $inactive_items = new \Doctrine\Common\Collections\ArrayCollection();

        foreach($all_items as $item){
            if($item->getActive() == 0){
                $inactive_items->add($item);
            }else{}
        }
        return $inactive_items;
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
}
