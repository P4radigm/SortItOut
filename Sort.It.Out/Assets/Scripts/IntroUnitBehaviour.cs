using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class IntroUnitBehaviour : MonoBehaviour
{
    [SerializeField] private Color[] colors;

    // Update is called once per frame
    void Update()
    {
        if(GameManager.instance.gameState == GameManager.GameStates.menu)
		{
            if(Input.touchCount < colors.Length)
			{
                GetComponent<SpriteRenderer>().color = colors[Input.touchCount];
                Debug.Log("Color is changing");
			}
		}
    }
}
