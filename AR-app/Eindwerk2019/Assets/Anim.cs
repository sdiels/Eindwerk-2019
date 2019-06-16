using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Anim : MonoBehaviour
{
    public Animator anim;
    public GameObject shoe;


    // Start is called before the first frame update
    void Start()
    {
        shoe.active = false;
    }

    // Update is called once per frame
    void Update()
    {
        if(Input.touchCount > 0)
        {
            shoe.active = true;
            if(anim.GetCurrentAnimatorStateInfo(0).IsName("Idle"))
            {
                anim.Play("Anim");
            }
        }
    }
}
