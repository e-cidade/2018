<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
//CLASSE DA ENTIDADE gerfs13
class cl_gerfs13 { 
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
   var $r35_anousu = 0; 
   var $r35_mesusu = 0; 
   var $r35_regist = 0; 
   var $r35_rubric = null; 
   var $r35_valor = 0; 
   var $r35_pd = 0; 
   var $r35_quant = 0; 
   var $r35_lotac = null; 
   var $r35_semest = 0; 
   var $r35_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r35_anousu = int4 = Ano do Exercicio 
                 r35_mesusu = int4 = Mes do Exercicio 
                 r35_regist = int4 = Codigo do Funcionario 
                 r35_rubric = char(4) = Rubrica 
                 r35_valor = float8 = valor da Rubrica 
                 r35_pd = int4 = Provento ou desconto 
                 r35_quant = float8 = Quantidade lancada na Rubrica 
                 r35_lotac = char(4) = Lotação 
                 r35_semest = int4 = Semestre do ano 
                 r35_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_gerfs13() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("gerfs13"); 
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
       $this->r35_anousu = ($this->r35_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r35_anousu"]:$this->r35_anousu);
       $this->r35_mesusu = ($this->r35_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r35_mesusu"]:$this->r35_mesusu);
       $this->r35_regist = ($this->r35_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r35_regist"]:$this->r35_regist);
       $this->r35_rubric = ($this->r35_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r35_rubric"]:$this->r35_rubric);
       $this->r35_valor = ($this->r35_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r35_valor"]:$this->r35_valor);
       $this->r35_pd = ($this->r35_pd == ""?@$GLOBALS["HTTP_POST_VARS"]["r35_pd"]:$this->r35_pd);
       $this->r35_quant = ($this->r35_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r35_quant"]:$this->r35_quant);
       $this->r35_lotac = ($this->r35_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r35_lotac"]:$this->r35_lotac);
       $this->r35_semest = ($this->r35_semest == ""?@$GLOBALS["HTTP_POST_VARS"]["r35_semest"]:$this->r35_semest);
       $this->r35_instit = ($this->r35_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r35_instit"]:$this->r35_instit);
     }else{
       $this->r35_anousu = ($this->r35_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r35_anousu"]:$this->r35_anousu);
       $this->r35_mesusu = ($this->r35_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r35_mesusu"]:$this->r35_mesusu);
       $this->r35_regist = ($this->r35_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r35_regist"]:$this->r35_regist);
       $this->r35_rubric = ($this->r35_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r35_rubric"]:$this->r35_rubric);
     }
   }
   // funcao para inclusao
   function incluir ($r35_anousu,$r35_mesusu,$r35_regist,$r35_rubric){ 
      $this->atualizacampos();
     if($this->r35_valor == null ){ 
       $this->erro_sql = " Campo valor da Rubrica nao Informado.";
       $this->erro_campo = "r35_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r35_pd == null ){ 
       $this->erro_sql = " Campo Provento ou desconto nao Informado.";
       $this->erro_campo = "r35_pd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r35_quant == null ){ 
       $this->erro_sql = " Campo Quantidade lancada na Rubrica nao Informado.";
       $this->erro_campo = "r35_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r35_lotac == null ){ 
       $this->erro_sql = " Campo Lotação nao Informado.";
       $this->erro_campo = "r35_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r35_semest == null ){ 
       $this->erro_sql = " Campo Semestre do ano nao Informado.";
       $this->erro_campo = "r35_semest";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r35_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "r35_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r35_anousu = $r35_anousu; 
       $this->r35_mesusu = $r35_mesusu; 
       $this->r35_regist = $r35_regist; 
       $this->r35_rubric = $r35_rubric; 
     if(($this->r35_anousu == null) || ($this->r35_anousu == "") ){ 
       $this->erro_sql = " Campo r35_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r35_mesusu == null) || ($this->r35_mesusu == "") ){ 
       $this->erro_sql = " Campo r35_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r35_regist == null) || ($this->r35_regist == "") ){ 
       $this->erro_sql = " Campo r35_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r35_rubric == null) || ($this->r35_rubric == "") ){ 
       $this->erro_sql = " Campo r35_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into gerfs13(
                                       r35_anousu 
                                      ,r35_mesusu 
                                      ,r35_regist 
                                      ,r35_rubric 
                                      ,r35_valor 
                                      ,r35_pd 
                                      ,r35_quant 
                                      ,r35_lotac 
                                      ,r35_semest 
                                      ,r35_instit 
                       )
                values (
                                $this->r35_anousu 
                               ,$this->r35_mesusu 
                               ,$this->r35_regist 
                               ,'$this->r35_rubric' 
                               ,$this->r35_valor 
                               ,$this->r35_pd 
                               ,$this->r35_quant 
                               ,'$this->r35_lotac' 
                               ,$this->r35_semest 
                               ,$this->r35_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivo do 13 salario ($this->r35_anousu."-".$this->r35_mesusu."-".$this->r35_regist."-".$this->r35_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivo do 13 salario já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivo do 13 salario ($this->r35_anousu."-".$this->r35_mesusu."-".$this->r35_regist."-".$this->r35_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r35_anousu."-".$this->r35_mesusu."-".$this->r35_regist."-".$this->r35_rubric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r35_anousu,$this->r35_mesusu,$this->r35_regist,$this->r35_rubric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,3987,'$this->r35_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3988,'$this->r35_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,3989,'$this->r35_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,3990,'$this->r35_rubric','I')");
       $resac = db_query("insert into db_acount values($acount,558,3987,'','".AddSlashes(pg_result($resaco,0,'r35_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,558,3988,'','".AddSlashes(pg_result($resaco,0,'r35_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,558,3989,'','".AddSlashes(pg_result($resaco,0,'r35_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,558,3990,'','".AddSlashes(pg_result($resaco,0,'r35_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,558,3991,'','".AddSlashes(pg_result($resaco,0,'r35_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,558,3992,'','".AddSlashes(pg_result($resaco,0,'r35_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,558,3993,'','".AddSlashes(pg_result($resaco,0,'r35_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,558,3994,'','".AddSlashes(pg_result($resaco,0,'r35_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,558,3995,'','".AddSlashes(pg_result($resaco,0,'r35_semest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,558,7459,'','".AddSlashes(pg_result($resaco,0,'r35_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r35_anousu=null,$r35_mesusu=null,$r35_regist=null,$r35_rubric=null) { 
      $this->atualizacampos();
     $sql = " update gerfs13 set ";
     $virgula = "";
     if(trim($this->r35_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r35_anousu"])){ 
       $sql  .= $virgula." r35_anousu = $this->r35_anousu ";
       $virgula = ",";
       if(trim($this->r35_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r35_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r35_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r35_mesusu"])){ 
       $sql  .= $virgula." r35_mesusu = $this->r35_mesusu ";
       $virgula = ",";
       if(trim($this->r35_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r35_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r35_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r35_regist"])){ 
       $sql  .= $virgula." r35_regist = $this->r35_regist ";
       $virgula = ",";
       if(trim($this->r35_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario nao Informado.";
         $this->erro_campo = "r35_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r35_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r35_rubric"])){ 
       $sql  .= $virgula." r35_rubric = '$this->r35_rubric' ";
       $virgula = ",";
       if(trim($this->r35_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "r35_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r35_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r35_valor"])){ 
       $sql  .= $virgula." r35_valor = $this->r35_valor ";
       $virgula = ",";
       if(trim($this->r35_valor) == null ){ 
         $this->erro_sql = " Campo valor da Rubrica nao Informado.";
         $this->erro_campo = "r35_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r35_pd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r35_pd"])){ 
       $sql  .= $virgula." r35_pd = $this->r35_pd ";
       $virgula = ",";
       if(trim($this->r35_pd) == null ){ 
         $this->erro_sql = " Campo Provento ou desconto nao Informado.";
         $this->erro_campo = "r35_pd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r35_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r35_quant"])){ 
       $sql  .= $virgula." r35_quant = $this->r35_quant ";
       $virgula = ",";
       if(trim($this->r35_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade lancada na Rubrica nao Informado.";
         $this->erro_campo = "r35_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r35_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r35_lotac"])){ 
       $sql  .= $virgula." r35_lotac = '$this->r35_lotac' ";
       $virgula = ",";
       if(trim($this->r35_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação nao Informado.";
         $this->erro_campo = "r35_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r35_semest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r35_semest"])){ 
       $sql  .= $virgula." r35_semest = $this->r35_semest ";
       $virgula = ",";
       if(trim($this->r35_semest) == null ){ 
         $this->erro_sql = " Campo Semestre do ano nao Informado.";
         $this->erro_campo = "r35_semest";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r35_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r35_instit"])){ 
       $sql  .= $virgula." r35_instit = $this->r35_instit ";
       $virgula = ",";
       if(trim($this->r35_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "r35_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r35_anousu!=null){
       $sql .= " r35_anousu = $this->r35_anousu";
     }
     if($r35_mesusu!=null){
       $sql .= " and  r35_mesusu = $this->r35_mesusu";
     }
     if($r35_regist!=null){
       $sql .= " and  r35_regist = $this->r35_regist";
     }
     if($r35_rubric!=null){
       $sql .= " and  r35_rubric = '$this->r35_rubric'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r35_anousu,$this->r35_mesusu,$this->r35_regist,$this->r35_rubric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3987,'$this->r35_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3988,'$this->r35_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,3989,'$this->r35_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,3990,'$this->r35_rubric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r35_anousu"]) || $this->r35_anousu != "")
           $resac = db_query("insert into db_acount values($acount,558,3987,'".AddSlashes(pg_result($resaco,$conresaco,'r35_anousu'))."','$this->r35_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r35_mesusu"]) || $this->r35_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,558,3988,'".AddSlashes(pg_result($resaco,$conresaco,'r35_mesusu'))."','$this->r35_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r35_regist"]) || $this->r35_regist != "")
           $resac = db_query("insert into db_acount values($acount,558,3989,'".AddSlashes(pg_result($resaco,$conresaco,'r35_regist'))."','$this->r35_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r35_rubric"]) || $this->r35_rubric != "")
           $resac = db_query("insert into db_acount values($acount,558,3990,'".AddSlashes(pg_result($resaco,$conresaco,'r35_rubric'))."','$this->r35_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r35_valor"]) || $this->r35_valor != "")
           $resac = db_query("insert into db_acount values($acount,558,3991,'".AddSlashes(pg_result($resaco,$conresaco,'r35_valor'))."','$this->r35_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r35_pd"]) || $this->r35_pd != "")
           $resac = db_query("insert into db_acount values($acount,558,3992,'".AddSlashes(pg_result($resaco,$conresaco,'r35_pd'))."','$this->r35_pd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r35_quant"]) || $this->r35_quant != "")
           $resac = db_query("insert into db_acount values($acount,558,3993,'".AddSlashes(pg_result($resaco,$conresaco,'r35_quant'))."','$this->r35_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r35_lotac"]) || $this->r35_lotac != "")
           $resac = db_query("insert into db_acount values($acount,558,3994,'".AddSlashes(pg_result($resaco,$conresaco,'r35_lotac'))."','$this->r35_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r35_semest"]) || $this->r35_semest != "")
           $resac = db_query("insert into db_acount values($acount,558,3995,'".AddSlashes(pg_result($resaco,$conresaco,'r35_semest'))."','$this->r35_semest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r35_instit"]) || $this->r35_instit != "")
           $resac = db_query("insert into db_acount values($acount,558,7459,'".AddSlashes(pg_result($resaco,$conresaco,'r35_instit'))."','$this->r35_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo do 13 salario nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r35_anousu."-".$this->r35_mesusu."-".$this->r35_regist."-".$this->r35_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo do 13 salario nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r35_anousu."-".$this->r35_mesusu."-".$this->r35_regist."-".$this->r35_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r35_anousu."-".$this->r35_mesusu."-".$this->r35_regist."-".$this->r35_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r35_anousu=null,$r35_mesusu=null,$r35_regist=null,$r35_rubric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r35_anousu,$r35_mesusu,$r35_regist,$r35_rubric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3987,'$r35_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3988,'$r35_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,3989,'$r35_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,3990,'$r35_rubric','E')");
         $resac = db_query("insert into db_acount values($acount,558,3987,'','".AddSlashes(pg_result($resaco,$iresaco,'r35_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,558,3988,'','".AddSlashes(pg_result($resaco,$iresaco,'r35_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,558,3989,'','".AddSlashes(pg_result($resaco,$iresaco,'r35_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,558,3990,'','".AddSlashes(pg_result($resaco,$iresaco,'r35_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,558,3991,'','".AddSlashes(pg_result($resaco,$iresaco,'r35_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,558,3992,'','".AddSlashes(pg_result($resaco,$iresaco,'r35_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,558,3993,'','".AddSlashes(pg_result($resaco,$iresaco,'r35_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,558,3994,'','".AddSlashes(pg_result($resaco,$iresaco,'r35_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,558,3995,'','".AddSlashes(pg_result($resaco,$iresaco,'r35_semest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,558,7459,'','".AddSlashes(pg_result($resaco,$iresaco,'r35_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from gerfs13
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r35_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r35_anousu = $r35_anousu ";
        }
        if($r35_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r35_mesusu = $r35_mesusu ";
        }
        if($r35_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r35_regist = $r35_regist ";
        }
        if($r35_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r35_rubric = '$r35_rubric' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo do 13 salario nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r35_anousu."-".$r35_mesusu."-".$r35_regist."-".$r35_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo do 13 salario nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r35_anousu."-".$r35_mesusu."-".$r35_regist."-".$r35_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r35_anousu."-".$r35_mesusu."-".$r35_regist."-".$r35_rubric;
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
        $this->erro_sql   = "Record Vazio na Tabela:gerfs13";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $r35_anousu=null,$r35_mesusu=null,$r35_regist=null,$r35_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfs13 ";
     $sql .= "      inner join db_config  on  db_config.codigo = gerfs13.r35_instit";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = gerfs13.r35_anousu 
		                                   and  lotacao.r13_mesusu = gerfs13.r35_mesusu 
																			 and  lotacao.r13_codigo = gerfs13.r35_lotac
																			 and  lotacao.r13_instit = gerfs13.r35_instit ";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = gerfs13.r35_anousu 
		                                   and  pessoal.r01_mesusu = gerfs13.r35_mesusu 
																			 and  pessoal.r01_regist = gerfs13.r35_regist 
																			 and  pessoal.r01_instit = gerfs13.r35_instit ";
     $sql .= "      inner join rubricas  on  rubricas.r06_anousu = gerfs13.r35_anousu 
		                                    and  rubricas.r06_mesusu = gerfs13.r35_mesusu 
																				and  rubricas.r06_codigo = gerfs13.r35_rubric
																				and  rubricas.r06_instit = gerfs13.r35_instit ";
     $sql .= "      inner join cgm  as d on   d.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join funcao  as d on   d.r37_anousu = pessoal.r01_anousu 
		                                       and   d.r37_mesusu = pessoal.r01_mesusu 
																					 and   d.r37_funcao = pessoal.r01_funcao
																					 and   d.r37_instit = pessoal.r01_instit ";
     $sql .= "      inner join inssirf  as d on   d.r33_anousu = pessoal.r01_anousu 
		                                        and   d.r33_mesusu = pessoal.r01_mesusu 
																						and   d.r33_codtab = pessoal.r01_tbprev
																						and   d.r33_instit = pessoal.r01_instit ";
     $sql .= "      inner join cargo  as d on   d.r65_anousu = pessoal.r01_anousu 
		                                      and   d.r65_mesusu = pessoal.r01_mesusu 
																					and   d.r65_cargo = pessoal.r01_cargo";
     $sql2 = "";
     if($dbwhere==""){
       if($r35_anousu!=null ){
         $sql2 .= " where gerfs13.r35_anousu = $r35_anousu "; 
       } 
       if($r35_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfs13.r35_mesusu = $r35_mesusu "; 
       } 
       if($r35_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfs13.r35_regist = $r35_regist "; 
       } 
       if($r35_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfs13.r35_rubric = '$r35_rubric' "; 
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
   // funcao do sql 
   function sql_query_file ( $r35_anousu=null,$r35_mesusu=null,$r35_regist=null,$r35_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfs13 ";
     $sql2 = "";
     if($dbwhere==""){
       if($r35_anousu!=null ){
         $sql2 .= " where gerfs13.r35_anousu = $r35_anousu "; 
       } 
       if($r35_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfs13.r35_mesusu = $r35_mesusu "; 
       } 
       if($r35_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfs13.r35_regist = $r35_regist "; 
       } 
       if($r35_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfs13.r35_rubric = '$r35_rubric' "; 
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
   function sql_query_rubricas ( $r35_anousu=null,$r35_mesusu=null,$r35_regist=null,$r35_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfs13 ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = gerfs13.r35_rubric
		                                      and  rhrubricas.rh27_instit = gerfs13.r35_instit ";
     $sql2 = "";
     if($dbwhere==""){
       if($r35_anousu!=null ){
         $sql2 .= " where gerfs13.r35_anousu = $r35_anousu "; 
       } 
       if($r35_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfs13.r35_mesusu = $r35_mesusu "; 
       } 
       if($r35_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfs13.r35_regist = $r35_regist "; 
       } 
       if($r35_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfs13.r35_rubric = '$r35_rubric' "; 
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
   function sql_query_seleciona( $r35_anousu=null,$r35_mesusu=null,$r35_regist=null,$r35_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfs13 ";
     $sql .= "      inner join rhpessoal   on  rhpessoal.rh01_regist = gerfs13.r35_regist ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = gerfs13.r35_rubric
		                                      and  rhrubricas.rh27_instit = gerfs13.r35_instit ";
     $sql .= "      inner join rhlota      on  rhlota.r70_codigo = to_number(gerfs13.r35_lotac, '9999')::integer
		                                      and  rhlota.r70_instit =  gerfs13.r35_instit ";
     $sql .= "      inner join cgm         on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r35_anousu!=null ){
         $sql2 .= " where gerfs13.r35_anousu = $r35_anousu "; 
       } 
       if($r35_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfs13.r35_mesusu = $r35_mesusu "; 
       } 
       if($r35_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfs13.r35_regist = $r35_regist "; 
       } 
       if($r35_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfs13.r35_rubric = '$r35_rubric' "; 
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
  
  function sql_query_servincsal13($r35_anousu=null,$r35_mesusu=null,$r35_regist=null,$r35_rubric=null,$campos="*",$ordem=null,$dbwhere="") { 
  	
     $sql = "select ";
     if ($campos != "*" ) {
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from gerfs13 ";
     $sql .= "      inner join rhresponsavelregist on rhresponsavelregist.rh108_regist = gerfs13.r35_regist ";
     $sql2 = "";
     if($dbwhere==""){
       if($r35_anousu!=null ){
         $sql2 .= " where gerfs13.r35_anousu = $r35_anousu "; 
       } 
       if($r35_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfs13.r35_mesusu = $r35_mesusu "; 
       } 
       if($r35_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfs13.r35_regist = $r35_regist "; 
       } 
       if($r35_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfs13.r35_rubric = '$r35_rubric' "; 
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

  public function migraGerfS13($iInstituicao) {
    
    $sSql  = "create table w_migracao_13salario as select distinct r34_anousu,                                                       ";
    $sSql .= "                                                     r34_mesusu,                                                       ";
    $sSql .= "                                                     r34_instit                                                        ";
    $sSql .= "                                           from pontof13                                                               ";
    $sSql .= "                                           inner join gerfs13 on r34_anousu = r35_anousu                               ";
    $sSql .= "                                                             and r34_mesusu = r35_mesusu                               ";
    $sSql .= "                                                             and r34_instit = {$iInstituicao};                         ";
    $sSql .= "                                                                                                                       ";
    
    $sSql .= "insert into rhfolhapagamento                                                                                           ";
    $sSql .= "select nextval('rhfolhapagamento_rh141_sequencial_seq'),                                                               ";
    $sSql .= "       0,                                                                                                              ";
    $sSql .= "       r34_anousu,                                                                                                     ";
    $sSql .= "       r34_mesusu,                                                                                                     ";
    $sSql .= "       r34_anousu,                                                                                                     ";
    $sSql .= "       r34_mesusu,                                                                                                     ";
    $sSql .= "       r34_instit,                                                                                                     ";
    $sSql .= "       5,                                                                                                              ";
    $sSql .= "       false,                                                                                                          ";
    $sSql .= "       'Folha 13º Salário número: 0 da competência: ' || r34_anousu || '/' || r34_mesusu || ' gerada automaticamente.' ";
    $sSql .= "  from w_migracao_13salario                                                                                            ";
    $sSql .= "order by r34_anousu asc,                                                                                               ";
    $sSql .= "         r34_mesusu asc;                                                                                               ";
  
    return $sSql;
  }
}
