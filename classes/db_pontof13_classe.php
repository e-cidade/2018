<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: pessoal
//CLASSE DA ENTIDADE pontof13
class cl_pontof13 { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $r34_anousu = 0; 
   var $r34_mesusu = 0; 
   var $r34_regist = 0; 
   var $r34_rubric = null; 
   var $r34_valor = 0; 
   var $r34_quant = 0; 
   var $r34_lotac = null; 
   var $r34_media = 0; 
   var $r34_calc = 0; 
   var $r34_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r34_anousu = int4 = Ano do Exercicio 
                 r34_mesusu = int4 = Mes do Exercicio 
                 r34_regist = int4 = Matrícula 
                 r34_rubric = char(4) = Rubrica 
                 r34_valor = float8 = Valor 
                 r34_quant = float8 = Quantidade 
                 r34_lotac = char(4) = Lotação 
                 r34_media = int4 = Numero de meses incidido 
                 r34_calc = int4 = Formula para calculo 
                 r34_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_pontof13() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pontof13"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->r34_anousu = ($this->r34_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r34_anousu"]:$this->r34_anousu);
       $this->r34_mesusu = ($this->r34_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r34_mesusu"]:$this->r34_mesusu);
       $this->r34_regist = ($this->r34_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r34_regist"]:$this->r34_regist);
       $this->r34_rubric = ($this->r34_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r34_rubric"]:$this->r34_rubric);
       $this->r34_valor = ($this->r34_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r34_valor"]:$this->r34_valor);
       $this->r34_quant = ($this->r34_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r34_quant"]:$this->r34_quant);
       $this->r34_lotac = ($this->r34_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r34_lotac"]:$this->r34_lotac);
       $this->r34_media = ($this->r34_media == ""?@$GLOBALS["HTTP_POST_VARS"]["r34_media"]:$this->r34_media);
       $this->r34_calc = ($this->r34_calc == ""?@$GLOBALS["HTTP_POST_VARS"]["r34_calc"]:$this->r34_calc);
       $this->r34_instit = ($this->r34_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r34_instit"]:$this->r34_instit);
     }else{
       $this->r34_anousu = ($this->r34_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r34_anousu"]:$this->r34_anousu);
       $this->r34_mesusu = ($this->r34_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r34_mesusu"]:$this->r34_mesusu);
       $this->r34_regist = ($this->r34_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r34_regist"]:$this->r34_regist);
       $this->r34_rubric = ($this->r34_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r34_rubric"]:$this->r34_rubric);
     }
   }
   // funcao para inclusao
   function incluir ($r34_anousu,$r34_mesusu,$r34_regist,$r34_rubric){ 
      $this->atualizacampos();
     if($this->r34_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "r34_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r34_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "r34_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r34_lotac == null ){ 
       $this->erro_sql = " Campo Lotação nao Informado.";
       $this->erro_campo = "r34_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r34_media == null ){ 
       $this->erro_sql = " Campo Numero de meses incidido nao Informado.";
       $this->erro_campo = "r34_media";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r34_calc == null ){ 
       $this->erro_sql = " Campo Formula para calculo nao Informado.";
       $this->erro_campo = "r34_calc";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r34_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "r34_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r34_anousu = $r34_anousu; 
       $this->r34_mesusu = $r34_mesusu; 
       $this->r34_regist = $r34_regist; 
       $this->r34_rubric = $r34_rubric; 
     if(($this->r34_anousu == null) || ($this->r34_anousu == "") ){ 
       $this->erro_sql = " Campo r34_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r34_mesusu == null) || ($this->r34_mesusu == "") ){ 
       $this->erro_sql = " Campo r34_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r34_regist == null) || ($this->r34_regist == "") ){ 
       $this->erro_sql = " Campo r34_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r34_rubric == null) || ($this->r34_rubric == "") ){ 
       $this->erro_sql = " Campo r34_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pontof13(
                                       r34_anousu 
                                      ,r34_mesusu 
                                      ,r34_regist 
                                      ,r34_rubric 
                                      ,r34_valor 
                                      ,r34_quant 
                                      ,r34_lotac 
                                      ,r34_media 
                                      ,r34_calc 
                                      ,r34_instit 
                       )
                values (
                                $this->r34_anousu 
                               ,$this->r34_mesusu 
                               ,$this->r34_regist 
                               ,'$this->r34_rubric' 
                               ,$this->r34_valor 
                               ,$this->r34_quant 
                               ,'$this->r34_lotac' 
                               ,$this->r34_media 
                               ,$this->r34_calc 
                               ,$this->r34_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ponto de 13o. salario ($this->r34_anousu."-".$this->r34_mesusu."-".$this->r34_regist."-".$this->r34_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ponto de 13o. salario já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ponto de 13o. salario ($this->r34_anousu."-".$this->r34_mesusu."-".$this->r34_regist."-".$this->r34_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r34_anousu."-".$this->r34_mesusu."-".$this->r34_regist."-".$this->r34_rubric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r34_anousu,$this->r34_mesusu,$this->r34_regist,$this->r34_rubric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4281,'$this->r34_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4282,'$this->r34_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4283,'$this->r34_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,4284,'$this->r34_rubric','I')");
       $resac = db_query("insert into db_acount values($acount,575,4281,'','".AddSlashes(pg_result($resaco,0,'r34_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,575,4282,'','".AddSlashes(pg_result($resaco,0,'r34_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,575,4283,'','".AddSlashes(pg_result($resaco,0,'r34_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,575,4284,'','".AddSlashes(pg_result($resaco,0,'r34_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,575,4285,'','".AddSlashes(pg_result($resaco,0,'r34_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,575,4286,'','".AddSlashes(pg_result($resaco,0,'r34_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,575,4287,'','".AddSlashes(pg_result($resaco,0,'r34_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,575,4288,'','".AddSlashes(pg_result($resaco,0,'r34_media'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,575,4289,'','".AddSlashes(pg_result($resaco,0,'r34_calc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,575,7462,'','".AddSlashes(pg_result($resaco,0,'r34_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r34_anousu=null,$r34_mesusu=null,$r34_regist=null,$r34_rubric=null,$where="") { 
      $this->atualizacampos();
     $sql = " update pontof13 set ";
     $virgula = "";
     if(trim($this->r34_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r34_anousu"])){ 
       $sql  .= $virgula." r34_anousu = $this->r34_anousu ";
       $virgula = ",";
       if(trim($this->r34_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r34_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r34_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r34_mesusu"])){ 
       $sql  .= $virgula." r34_mesusu = $this->r34_mesusu ";
       $virgula = ",";
       if(trim($this->r34_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r34_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r34_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r34_regist"])){ 
       $sql  .= $virgula." r34_regist = $this->r34_regist ";
       $virgula = ",";
       if(trim($this->r34_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "r34_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r34_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r34_rubric"])){ 
       $sql  .= $virgula." r34_rubric = '$this->r34_rubric' ";
       $virgula = ",";
       if(trim($this->r34_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "r34_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r34_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r34_valor"])){ 
       $sql  .= $virgula." r34_valor = $this->r34_valor ";
       $virgula = ",";
       if(trim($this->r34_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "r34_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r34_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r34_quant"])){ 
       $sql  .= $virgula." r34_quant = $this->r34_quant ";
       $virgula = ",";
       if(trim($this->r34_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "r34_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r34_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r34_lotac"])){ 
       $sql  .= $virgula." r34_lotac = '$this->r34_lotac' ";
       $virgula = ",";
       if(trim($this->r34_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação nao Informado.";
         $this->erro_campo = "r34_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r34_media)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r34_media"])){ 
       $sql  .= $virgula." r34_media = $this->r34_media ";
       $virgula = ",";
       if(trim($this->r34_media) == null ){ 
         $this->erro_sql = " Campo Numero de meses incidido nao Informado.";
         $this->erro_campo = "r34_media";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r34_calc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r34_calc"])){ 
       $sql  .= $virgula." r34_calc = $this->r34_calc ";
       $virgula = ",";
       if(trim($this->r34_calc) == null ){ 
         $this->erro_sql = " Campo Formula para calculo nao Informado.";
         $this->erro_campo = "r34_calc";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r34_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r34_instit"])){ 
       $sql  .= $virgula." r34_instit = $this->r34_instit ";
       $virgula = ",";
       if(trim($this->r34_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "r34_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r34_anousu!=null){
       $sql .= " r34_anousu = $this->r34_anousu";
     }
     if($r34_mesusu!=null){
       $sql .= " and  r34_mesusu = $this->r34_mesusu";
     }
     if($r34_regist!=null){
       $sql .= " and  r34_regist = $this->r34_regist";
     }
     if($r34_rubric!=null){
       $sql .= " and  r34_rubric = '$this->r34_rubric'";
     }
     if(trim($where) != ""){
	     if(strpos("where",$sql) != ""){
	     	 $sql .= " and ";
	     }
	     $sql .= $where;
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r34_anousu,$this->r34_mesusu,$this->r34_regist,$this->r34_rubric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4281,'$this->r34_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4282,'$this->r34_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4283,'$this->r34_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,4284,'$this->r34_rubric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r34_anousu"]) || $this->r34_anousu != "")
           $resac = db_query("insert into db_acount values($acount,575,4281,'".AddSlashes(pg_result($resaco,$conresaco,'r34_anousu'))."','$this->r34_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r34_mesusu"]) || $this->r34_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,575,4282,'".AddSlashes(pg_result($resaco,$conresaco,'r34_mesusu'))."','$this->r34_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r34_regist"]) || $this->r34_regist != "")
           $resac = db_query("insert into db_acount values($acount,575,4283,'".AddSlashes(pg_result($resaco,$conresaco,'r34_regist'))."','$this->r34_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r34_rubric"]) || $this->r34_rubric != "")
           $resac = db_query("insert into db_acount values($acount,575,4284,'".AddSlashes(pg_result($resaco,$conresaco,'r34_rubric'))."','$this->r34_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r34_valor"]) || $this->r34_valor != "")
           $resac = db_query("insert into db_acount values($acount,575,4285,'".AddSlashes(pg_result($resaco,$conresaco,'r34_valor'))."','$this->r34_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r34_quant"]) || $this->r34_quant != "")
           $resac = db_query("insert into db_acount values($acount,575,4286,'".AddSlashes(pg_result($resaco,$conresaco,'r34_quant'))."','$this->r34_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r34_lotac"]) || $this->r34_lotac != "")
           $resac = db_query("insert into db_acount values($acount,575,4287,'".AddSlashes(pg_result($resaco,$conresaco,'r34_lotac'))."','$this->r34_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r34_media"]) || $this->r34_media != "")
           $resac = db_query("insert into db_acount values($acount,575,4288,'".AddSlashes(pg_result($resaco,$conresaco,'r34_media'))."','$this->r34_media',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r34_calc"]) || $this->r34_calc != "")
           $resac = db_query("insert into db_acount values($acount,575,4289,'".AddSlashes(pg_result($resaco,$conresaco,'r34_calc'))."','$this->r34_calc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r34_instit"]) || $this->r34_instit != "")
           $resac = db_query("insert into db_acount values($acount,575,7462,'".AddSlashes(pg_result($resaco,$conresaco,'r34_instit'))."','$this->r34_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ponto de 13o. salario nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r34_anousu."-".$this->r34_mesusu."-".$this->r34_regist."-".$this->r34_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ponto de 13o. salario nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r34_anousu."-".$this->r34_mesusu."-".$this->r34_regist."-".$this->r34_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r34_anousu."-".$this->r34_mesusu."-".$this->r34_regist."-".$this->r34_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r34_anousu=null,$r34_mesusu=null,$r34_regist=null,$r34_rubric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r34_anousu,$r34_mesusu,$r34_regist,$r34_rubric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4281,'$r34_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4282,'$r34_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4283,'$r34_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,4284,'$r34_rubric','E')");
         $resac = db_query("insert into db_acount values($acount,575,4281,'','".AddSlashes(pg_result($resaco,$iresaco,'r34_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,575,4282,'','".AddSlashes(pg_result($resaco,$iresaco,'r34_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,575,4283,'','".AddSlashes(pg_result($resaco,$iresaco,'r34_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,575,4284,'','".AddSlashes(pg_result($resaco,$iresaco,'r34_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,575,4285,'','".AddSlashes(pg_result($resaco,$iresaco,'r34_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,575,4286,'','".AddSlashes(pg_result($resaco,$iresaco,'r34_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,575,4287,'','".AddSlashes(pg_result($resaco,$iresaco,'r34_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,575,4288,'','".AddSlashes(pg_result($resaco,$iresaco,'r34_media'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,575,4289,'','".AddSlashes(pg_result($resaco,$iresaco,'r34_calc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,575,7462,'','".AddSlashes(pg_result($resaco,$iresaco,'r34_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pontof13
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r34_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r34_anousu = $r34_anousu ";
        }
        if($r34_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r34_mesusu = $r34_mesusu ";
        }
        if($r34_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r34_regist = $r34_regist ";
        }
        if($r34_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r34_rubric = '$r34_rubric' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ponto de 13o. salario nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r34_anousu."-".$r34_mesusu."-".$r34_regist."-".$r34_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ponto de 13o. salario nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r34_anousu."-".$r34_mesusu."-".$r34_regist."-".$r34_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r34_anousu."-".$r34_mesusu."-".$r34_regist."-".$r34_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:pontof13";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r34_anousu=null,$r34_mesusu=null,$r34_regist=null,$r34_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from pontof13 ";
     $sql .= "      inner join db_config  on  db_config.codigo = pontof13.r34_instit";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = pontof13.r34_anousu 
		                                   and  lotacao.r13_mesusu = pontof13.r34_mesusu 
																			 and  lotacao.r13_codigo = pontof13.r34_lotac
																			 and  lotacao.r13_instit = pontof13.r34_instit ";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = pontof13.r34_anousu 
		                                   and  pessoal.r01_mesusu = pontof13.r34_mesusu 
																			 and  pessoal.r01_regist = pontof13.r34_regist
																			 and  pessoal.r01_instit = pontof13.r34_instit ";
     $sql .= "      inner join rubricas  on  rubricas.r06_anousu = pontof13.r34_anousu 
		                                    and  rubricas.r06_mesusu = pontof13.r34_mesusu 
																				and  rubricas.r06_codigo = pontof13.r34_rubric
																				and  rubricas.r06_instit = pontof13.r34_instit ";
     $sql .= "      inner join cgm  as d on   d.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  as d on d.r37_anousu = pessoal.r01_anousu 
		                                       and d.r37_mesusu = pessoal.r01_mesusu 
																					 and d.r37_funcao = pessoal.r01_funcao
																					 and d.r37_instit = pessoal.r01_instit ";
     $sql .= "      inner join inssirf  as d on d.r33_anousu = pessoal.r01_anousu 
		                                        and d.r33_mesusu = pessoal.r01_mesusu 
																						and d.r33_codtab = pessoal.r01_tbprev
																						and d.r33_instit = pessoal.r01_instit ";
     $sql .= "      inner join cargo  as d on d.r65_anousu = pessoal.r01_anousu 
		                                      and d.r65_mesusu = pessoal.r01_mesusu 
																					and d.r65_cargo = pessoal.r01_cargo";
     $sql2 = "";
     if($dbwhere==""){
       if($r34_anousu!=null ){
         $sql2 .= " where pontof13.r34_anousu = $r34_anousu "; 
       } 
       if($r34_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontof13.r34_mesusu = $r34_mesusu "; 
       } 
       if($r34_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontof13.r34_regist = $r34_regist "; 
       } 
       if($r34_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontof13.r34_rubric = '$r34_rubric' "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_file ( $r34_anousu=null,$r34_mesusu=null,$r34_regist=null,$r34_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from pontof13 ";
     $sql2 = "";
     if($dbwhere==""){
       if($r34_anousu!=null ){
         $sql2 .= " where pontof13.r34_anousu = $r34_anousu "; 
       } 
       if($r34_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontof13.r34_mesusu = $r34_mesusu "; 
       } 
       if($r34_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontof13.r34_regist = $r34_regist "; 
       } 
       if($r34_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontof13.r34_rubric = '$r34_rubric' "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   function sql_query_seleciona ( $r34_anousu=null,$r34_mesusu=null,$r34_regist=null,$r34_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from pontof13 ";
     $sql .= "      inner join rhpessoal    on  rhpessoal.rh01_regist = pontof13.r34_regist";
     $sql .= "      inner join rhpessoalmov on  rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist ";
     $sql .= "                             and  pontof13.r34_anousu          = rhpessoalmov.rh02_anousu ";
     $sql .= "                             and  pontof13.r34_mesusu          = rhpessoalmov.rh02_mesusu ";
     $sql .= "                             and  pontof13.r34_instit          = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join rhfuncao     on  rhpessoalmov.rh02_funcao    = rhfuncao.rh37_funcao     ";
     $sql .= "                             and  rhpessoalmov.rh02_instit    = rhfuncao.rh37_instit     ";
     $sql .= "      inner join rhregime     on  rhpessoalmov.rh02_codreg    = rhregime.rh30_codreg     ";
     $sql .= "                             and  rhpessoalmov.rh02_instit    = rhregime.rh30_instit     ";
     $sql .= "      inner join rhrubricas   on  rhrubricas.rh27_rubric = pontof13.r34_rubric
		                                       and  rhrubricas.rh27_instit = pontof13.r34_instit ";
     $sql .= "      inner join rhlota       on  rhlota.r70_codigo::char(12) = pontof13.r34_lotac
		                                       and  rhlota.r70_instit = pontof13.r34_instit ";
     $sql .= "      inner join cgm          on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r34_anousu!=null ){
         $sql2 .= " where pontof13.r34_anousu = $r34_anousu "; 
       } 
       if($r34_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontof13.r34_mesusu = $r34_mesusu "; 
       } 
       if($r34_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontof13.r34_regist = $r34_regist "; 
       } 
       if($r34_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontof13.r34_rubric = '$r34_rubric' "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>