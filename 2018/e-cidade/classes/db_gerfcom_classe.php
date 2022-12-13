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
//CLASSE DA ENTIDADE gerfcom
class cl_gerfcom { 
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
   var $r48_anousu = 0; 
   var $r48_mesusu = 0; 
   var $r48_regist = 0; 
   var $r48_rubric = null; 
   var $r48_valor = 0; 
   var $r48_pd = 0; 
   var $r48_quant = 0; 
   var $r48_lotac = null; 
   var $r48_semest = 0; 
   var $r48_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r48_anousu = int4 = Ano do Exercicio 
                 r48_mesusu = int4 = Mes do Exercicio 
                 r48_regist = int4 = Matrícula 
                 r48_rubric = char(4) = Rubrica 
                 r48_valor = float8 = Valor 
                 r48_pd = int4 = P/D 
                 r48_quant = float8 = Quantidade 
                 r48_lotac = char(4) = Lotação 
                 r48_semest = int4 = Semestre 
                 r48_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_gerfcom() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("gerfcom"); 
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
       $this->r48_anousu = ($this->r48_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r48_anousu"]:$this->r48_anousu);
       $this->r48_mesusu = ($this->r48_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r48_mesusu"]:$this->r48_mesusu);
       $this->r48_regist = ($this->r48_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r48_regist"]:$this->r48_regist);
       $this->r48_rubric = ($this->r48_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r48_rubric"]:$this->r48_rubric);
       $this->r48_valor = ($this->r48_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r48_valor"]:$this->r48_valor);
       $this->r48_pd = ($this->r48_pd == ""?@$GLOBALS["HTTP_POST_VARS"]["r48_pd"]:$this->r48_pd);
       $this->r48_quant = ($this->r48_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r48_quant"]:$this->r48_quant);
       $this->r48_lotac = ($this->r48_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r48_lotac"]:$this->r48_lotac);
       $this->r48_semest = ($this->r48_semest == ""?@$GLOBALS["HTTP_POST_VARS"]["r48_semest"]:$this->r48_semest);
       $this->r48_instit = ($this->r48_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r48_instit"]:$this->r48_instit);
     }else{
       $this->r48_anousu = ($this->r48_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r48_anousu"]:$this->r48_anousu);
       $this->r48_mesusu = ($this->r48_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r48_mesusu"]:$this->r48_mesusu);
       $this->r48_regist = ($this->r48_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r48_regist"]:$this->r48_regist);
       $this->r48_rubric = ($this->r48_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r48_rubric"]:$this->r48_rubric);
     }
   }
   // funcao para inclusao
   function incluir ($r48_anousu,$r48_mesusu,$r48_regist,$r48_rubric){ 
      $this->atualizacampos();
     if($this->r48_valor == null ){ 
       $this->erro_sql = " Campo Valor não informado.";
       $this->erro_campo = "r48_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r48_pd == null ){ 
       $this->erro_sql = " Campo P/D não informado.";
       $this->erro_campo = "r48_pd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r48_quant == null ){ 
       $this->erro_sql = " Campo Quantidade não informado.";
       $this->erro_campo = "r48_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r48_lotac == null ){ 
       $this->erro_sql = " Campo Lotação não informado.";
       $this->erro_campo = "r48_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r48_semest == null ){ 
       $this->erro_sql = " Campo Semestre não informado.";
       $this->erro_campo = "r48_semest";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r48_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao não informado.";
       $this->erro_campo = "r48_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r48_anousu = $r48_anousu; 
       $this->r48_mesusu = $r48_mesusu; 
       $this->r48_regist = $r48_regist; 
       $this->r48_rubric = $r48_rubric; 
     if(($this->r48_anousu == null) || ($this->r48_anousu == "") ){ 
       $this->erro_sql = " Campo r48_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r48_mesusu == null) || ($this->r48_mesusu == "") ){ 
       $this->erro_sql = " Campo r48_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r48_regist == null) || ($this->r48_regist == "") ){ 
       $this->erro_sql = " Campo r48_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r48_rubric == null) || ($this->r48_rubric == "") ){ 
       $this->erro_sql = " Campo r48_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into gerfcom(
                                       r48_anousu 
                                      ,r48_mesusu 
                                      ,r48_regist 
                                      ,r48_rubric 
                                      ,r48_valor 
                                      ,r48_pd 
                                      ,r48_quant 
                                      ,r48_lotac 
                                      ,r48_semest 
                                      ,r48_instit 
                       )
                values (
                                $this->r48_anousu 
                               ,$this->r48_mesusu 
                               ,$this->r48_regist 
                               ,'$this->r48_rubric' 
                               ,$this->r48_valor 
                               ,$this->r48_pd 
                               ,$this->r48_quant 
                               ,'$this->r48_lotac' 
                               ,$this->r48_semest 
                               ,$this->r48_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Calculo da folha complementar ($this->r48_anousu."-".$this->r48_mesusu."-".$this->r48_regist."-".$this->r48_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Calculo da folha complementar já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Calculo da folha complementar ($this->r48_anousu."-".$this->r48_mesusu."-".$this->r48_regist."-".$this->r48_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r48_anousu."-".$this->r48_mesusu."-".$this->r48_regist."-".$this->r48_rubric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r48_anousu,$this->r48_mesusu,$this->r48_regist,$this->r48_rubric  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,3949,'$this->r48_anousu','I')");
         $resac = db_query("insert into db_acountkey values($acount,3950,'$this->r48_mesusu','I')");
         $resac = db_query("insert into db_acountkey values($acount,3951,'$this->r48_regist','I')");
         $resac = db_query("insert into db_acountkey values($acount,3952,'$this->r48_rubric','I')");
         $resac = db_query("insert into db_acount values($acount,554,3949,'','".AddSlashes(pg_result($resaco,0,'r48_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,554,3950,'','".AddSlashes(pg_result($resaco,0,'r48_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,554,3951,'','".AddSlashes(pg_result($resaco,0,'r48_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,554,3952,'','".AddSlashes(pg_result($resaco,0,'r48_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,554,3953,'','".AddSlashes(pg_result($resaco,0,'r48_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,554,3954,'','".AddSlashes(pg_result($resaco,0,'r48_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,554,3955,'','".AddSlashes(pg_result($resaco,0,'r48_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,554,3956,'','".AddSlashes(pg_result($resaco,0,'r48_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,554,3957,'','".AddSlashes(pg_result($resaco,0,'r48_semest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,554,7455,'','".AddSlashes(pg_result($resaco,0,'r48_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($r48_anousu=null,$r48_mesusu=null,$r48_regist=null,$r48_rubric=null) { 
      $this->atualizacampos();
     $sql = " update gerfcom set ";
     $virgula = "";
     if(trim($this->r48_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r48_anousu"])){ 
       $sql  .= $virgula." r48_anousu = $this->r48_anousu ";
       $virgula = ",";
       if(trim($this->r48_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio não informado.";
         $this->erro_campo = "r48_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r48_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r48_mesusu"])){ 
       $sql  .= $virgula." r48_mesusu = $this->r48_mesusu ";
       $virgula = ",";
       if(trim($this->r48_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio não informado.";
         $this->erro_campo = "r48_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r48_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r48_regist"])){ 
       $sql  .= $virgula." r48_regist = $this->r48_regist ";
       $virgula = ",";
       if(trim($this->r48_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula não informado.";
         $this->erro_campo = "r48_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r48_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r48_rubric"])){ 
       $sql  .= $virgula." r48_rubric = '$this->r48_rubric' ";
       $virgula = ",";
       if(trim($this->r48_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica não informado.";
         $this->erro_campo = "r48_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r48_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r48_valor"])){ 
       $sql  .= $virgula." r48_valor = $this->r48_valor ";
       $virgula = ",";
       if(trim($this->r48_valor) == null ){ 
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "r48_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r48_pd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r48_pd"])){ 
       $sql  .= $virgula." r48_pd = $this->r48_pd ";
       $virgula = ",";
       if(trim($this->r48_pd) == null ){ 
         $this->erro_sql = " Campo P/D não informado.";
         $this->erro_campo = "r48_pd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r48_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r48_quant"])){ 
       $sql  .= $virgula." r48_quant = $this->r48_quant ";
       $virgula = ",";
       if(trim($this->r48_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade não informado.";
         $this->erro_campo = "r48_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r48_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r48_lotac"])){ 
       $sql  .= $virgula." r48_lotac = '$this->r48_lotac' ";
       $virgula = ",";
       if(trim($this->r48_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação não informado.";
         $this->erro_campo = "r48_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r48_semest)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r48_semest"])){ 
       $sql  .= $virgula." r48_semest = $this->r48_semest ";
       $virgula = ",";
       if(trim($this->r48_semest) == null ){ 
         $this->erro_sql = " Campo Semestre não informado.";
         $this->erro_campo = "r48_semest";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r48_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r48_instit"])){ 
       $sql  .= $virgula." r48_instit = $this->r48_instit ";
       $virgula = ",";
       if(trim($this->r48_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao não informado.";
         $this->erro_campo = "r48_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r48_anousu!=null){
       $sql .= " r48_anousu = $this->r48_anousu";
     }
     if($r48_mesusu!=null){
       $sql .= " and  r48_mesusu = $this->r48_mesusu";
     }
     if($r48_regist!=null){
       $sql .= " and  r48_regist = $this->r48_regist";
     }
     if($r48_rubric!=null){
       $sql .= " and  r48_rubric = '$this->r48_rubric'";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r48_anousu,$this->r48_mesusu,$this->r48_regist,$this->r48_rubric));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,3949,'$this->r48_anousu','A')");
           $resac = db_query("insert into db_acountkey values($acount,3950,'$this->r48_mesusu','A')");
           $resac = db_query("insert into db_acountkey values($acount,3951,'$this->r48_regist','A')");
           $resac = db_query("insert into db_acountkey values($acount,3952,'$this->r48_rubric','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r48_anousu"]) || $this->r48_anousu != "")
             $resac = db_query("insert into db_acount values($acount,554,3949,'".AddSlashes(pg_result($resaco,$conresaco,'r48_anousu'))."','$this->r48_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r48_mesusu"]) || $this->r48_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,554,3950,'".AddSlashes(pg_result($resaco,$conresaco,'r48_mesusu'))."','$this->r48_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r48_regist"]) || $this->r48_regist != "")
             $resac = db_query("insert into db_acount values($acount,554,3951,'".AddSlashes(pg_result($resaco,$conresaco,'r48_regist'))."','$this->r48_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r48_rubric"]) || $this->r48_rubric != "")
             $resac = db_query("insert into db_acount values($acount,554,3952,'".AddSlashes(pg_result($resaco,$conresaco,'r48_rubric'))."','$this->r48_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r48_valor"]) || $this->r48_valor != "")
             $resac = db_query("insert into db_acount values($acount,554,3953,'".AddSlashes(pg_result($resaco,$conresaco,'r48_valor'))."','$this->r48_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r48_pd"]) || $this->r48_pd != "")
             $resac = db_query("insert into db_acount values($acount,554,3954,'".AddSlashes(pg_result($resaco,$conresaco,'r48_pd'))."','$this->r48_pd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r48_quant"]) || $this->r48_quant != "")
             $resac = db_query("insert into db_acount values($acount,554,3955,'".AddSlashes(pg_result($resaco,$conresaco,'r48_quant'))."','$this->r48_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r48_lotac"]) || $this->r48_lotac != "")
             $resac = db_query("insert into db_acount values($acount,554,3956,'".AddSlashes(pg_result($resaco,$conresaco,'r48_lotac'))."','$this->r48_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r48_semest"]) || $this->r48_semest != "")
             $resac = db_query("insert into db_acount values($acount,554,3957,'".AddSlashes(pg_result($resaco,$conresaco,'r48_semest'))."','$this->r48_semest',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r48_instit"]) || $this->r48_instit != "")
             $resac = db_query("insert into db_acount values($acount,554,7455,'".AddSlashes(pg_result($resaco,$conresaco,'r48_instit'))."','$this->r48_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }

     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Calculo da folha complementar nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r48_anousu."-".$this->r48_mesusu."-".$this->r48_regist."-".$this->r48_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Calculo da folha complementar nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r48_anousu."-".$this->r48_mesusu."-".$this->r48_regist."-".$this->r48_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r48_anousu."-".$this->r48_mesusu."-".$this->r48_regist."-".$this->r48_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($r48_anousu=null,$r48_mesusu=null,$r48_regist=null,$r48_rubric=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($r48_anousu,$r48_mesusu,$r48_regist,$r48_rubric));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,3949,'$r48_anousu','E')");
           $resac  = db_query("insert into db_acountkey values($acount,3950,'$r48_mesusu','E')");
           $resac  = db_query("insert into db_acountkey values($acount,3951,'$r48_regist','E')");
           $resac  = db_query("insert into db_acountkey values($acount,3952,'$r48_rubric','E')");
           $resac  = db_query("insert into db_acount values($acount,554,3949,'','".AddSlashes(pg_result($resaco,$iresaco,'r48_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,554,3950,'','".AddSlashes(pg_result($resaco,$iresaco,'r48_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,554,3951,'','".AddSlashes(pg_result($resaco,$iresaco,'r48_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,554,3952,'','".AddSlashes(pg_result($resaco,$iresaco,'r48_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,554,3953,'','".AddSlashes(pg_result($resaco,$iresaco,'r48_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,554,3954,'','".AddSlashes(pg_result($resaco,$iresaco,'r48_pd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,554,3955,'','".AddSlashes(pg_result($resaco,$iresaco,'r48_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,554,3956,'','".AddSlashes(pg_result($resaco,$iresaco,'r48_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,554,3957,'','".AddSlashes(pg_result($resaco,$iresaco,'r48_semest'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,554,7455,'','".AddSlashes(pg_result($resaco,$iresaco,'r48_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from gerfcom
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($r48_anousu)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r48_anousu = $r48_anousu ";
        }
        if (!empty($r48_mesusu)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r48_mesusu = $r48_mesusu ";
        }
        if (!empty($r48_regist)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r48_regist = $r48_regist ";
        }
        if (!empty($r48_rubric)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r48_rubric = '$r48_rubric' ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Calculo da folha complementar nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r48_anousu."-".$r48_mesusu."-".$r48_regist."-".$r48_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Calculo da folha complementar nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r48_anousu."-".$r48_mesusu."-".$r48_regist."-".$r48_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r48_anousu."-".$r48_mesusu."-".$r48_regist."-".$r48_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   public function sql_record($sql) { 
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:gerfcom";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($r48_anousu = null,$r48_mesusu = null,$r48_regist = null,$r48_rubric = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from gerfcom ";
     $sql .= "      inner join db_config  on  db_config.codigo = gerfcom.r48_instit";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = gerfcom.r48_anousu and  lotacao.r13_mesusu = gerfcom.r48_mesusu and  lotacao.r13_codigo = gerfcom.r48_lotac";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = gerfcom.r48_anousu and  pessoal.r01_mesusu = gerfcom.r48_mesusu and  pessoal.r01_regist = gerfcom.r48_regist";
     $sql .= "      inner join rubricas  on  rubricas.r06_anousu = gerfcom.r48_anousu and  rubricas.r06_mesusu = gerfcom.r48_mesusu and  rubricas.r06_codigo = gerfcom.r48_rubric";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql .= "      inner join db_config  on  db_config.codigo = lotacao.r13_instit";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      left  join db_config  on  db_config.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  on  funcao.r37_anousu = pessoal.r01_anousu and  funcao.r37_mesusu = pessoal.r01_mesusu and  funcao.r37_funcao = pessoal.r01_funcao";
     $sql .= "      inner join lotacao  as b on   b.r13_anousu = pessoal.r01_anousu and   b.r13_mesusu = pessoal.r01_mesusu and   b.r13_codigo = pessoal.r01_lotac";
     $sql .= "      inner join cargo  on  cargo.r65_anousu = pessoal.r01_anousu and  cargo.r65_mesusu = pessoal.r01_mesusu and  cargo.r65_cargo = pessoal.r01_cargo";
     $sql .= "      inner join db_config  on  db_config.codigo = rubricas.r06_instit";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($r48_anousu)) {
         $sql2 .= " where gerfcom.r48_anousu = $r48_anousu "; 
       } 
       if (!empty($r48_mesusu)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfcom.r48_mesusu = $r48_mesusu "; 
       } 
       if (!empty($r48_regist)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfcom.r48_regist = $r48_regist "; 
       } 
       if (!empty($r48_rubric)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfcom.r48_rubric = '$r48_rubric' "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
   // funcao do sql 
   public function sql_query_file ($r48_anousu = null,$r48_mesusu = null,$r48_regist = null,$r48_rubric = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from gerfcom ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($r48_anousu)){
         $sql2 .= " where gerfcom.r48_anousu = $r48_anousu "; 
       } 
       if (!empty($r48_mesusu)){
         if ( !empty($sql2) ) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfcom.r48_mesusu = $r48_mesusu "; 
       } 
       if (!empty($r48_regist)){
         if ( !empty($sql2) ) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfcom.r48_regist = $r48_regist "; 
       } 
       if (!empty($r48_rubric)){
         if ( !empty($sql2) ) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfcom.r48_rubric = '$r48_rubric' "; 
       } 
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }

   function sql_query_rhrubricas ( $r48_anousu=null,$r48_mesusu=null,$r48_regist=null,$r48_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfcom ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = gerfcom.r48_rubric
		                                      and  rhrubricas.rh27_instit = gerfcom.r48_instit ";
     $sql2 = "";
     if($dbwhere==""){
       if($r48_anousu!=null ){
         $sql2 .= " where gerfcom.r48_anousu = $r48_anousu "; 
       } 
       if($r48_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfcom.r48_mesusu = $r48_mesusu "; 
       } 
       if($r48_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfcom.r48_regist = $r48_regist "; 
       } 
       if($r48_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfcom.r48_rubric = '$r48_rubric' "; 
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
   function sql_query_seleciona ( $r48_anousu=null,$r48_mesusu=null,$r48_regist=null,$r48_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from gerfcom ";
     $sql .= "      inner join rhpessoal   on  rhpessoal.rh01_regist = gerfcom.r48_regist ";
     $sql .= "      inner join rhrubricas  on  rhrubricas.rh27_rubric = gerfcom.r48_rubric
		                                      and  rhrubricas.rh27_instit = gerfcom.r48_instit ";
     $sql .= "      inner join rhlota      on  rhlota.r70_codigo = to_number(gerfcom.r48_lotac, '9999')::integer
		                                      and  rhlota.r70_instit = gerfcom.r48_instit ";
     $sql .= "      inner join cgm         on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r48_anousu!=null ){
         $sql2 .= " where gerfcom.r48_anousu = $r48_anousu "; 
       } 
       if($r48_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfcom.r48_mesusu = $r48_mesusu "; 
       } 
       if($r48_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfcom.r48_regist = $r48_regist "; 
       } 
       if($r48_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " gerfcom.r48_rubric = '$r48_rubric' "; 
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

  /**
   * Retorna uma query para ser utilizada na verificação das folhas complementares fechadas da competência atual 
   * @param integer $iAnoUsu
   * @param integer $iMesUsu
   * @return String 
   */
  public function sql_query_complementar_fechadas($iAnoUsu, $iMesUsu){
    
    $iInstituicao = db_getsession('DB_instit');

    $sSql         = "select distinct rh141_codigo from gerfcom";
    $sSql        .= " inner join rhfolhapagamento on rh141_codigo = r48_semest    ";
    $sSql        .= "                            and rh141_anousu = r48_anousu    ";
    $sSql        .= "                            and rh141_mesusu = r48_mesusu    ";
    $sSql        .= "where rh141_aberto is false and r48_anousu = {$iAnoUsu}      ";
    $sSql        .= "                            and r48_mesusu = {$iMesUsu}      ";
    $sSql        .= "                            and r48_instit = {$iInstituicao} ";
    $sSql        .= "                            and rh141_tipofolha = 3          ";

    return $sSql;
  }

  /**
   * Retorna dados que estao na rhhistoricocalculo e insere 
   * novamente na gerfcom na competência informada.
   * @param  Integer $iAno
   * @param  Integer $iMes
   * @return boolean
   */
  public function retornarDadosGerfCom($iAno, $iMes){

    $sSql  = " insert into gerfcom(                                                                                        ";
    $sSql .= "                      r48_anousu,                                                                            ";
    $sSql .= "                      r48_mesusu,                                                                            ";
    $sSql .= "                      r48_regist,                                                                            ";
    $sSql .= "                      r48_rubric,                                                                            ";
    $sSql .= "                      r48_valor,                                                                             ";
    $sSql .= "                      r48_pd,                                                                                ";
    $sSql .= "                      r48_quant,                                                                             ";
    $sSql .= "                      r48_lotac,                                                                             ";
    $sSql .= "                      r48_semest,                                                                            ";
    $sSql .= "                      r48_instit                                                                             ";
    $sSql .= " )                                                                                                           ";
    $sSql .= "                                                                                                             ";
    $sSql .= " select rh141_anousu,                                                                                        ";
    $sSql .= "        rh141_mesusu,                                                                                        ";
    $sSql .= "        rh143_regist,                                                                                        ";
    $sSql .= "        rh143_rubrica,                                                                                       ";
    $sSql .= "        rh143_valor,                                                                                         ";
    $sSql .= "        rh143_tipoevento,                                                                                    ";
    $sSql .= "        rh143_quantidade,                                                                                    ";
    $sSql .= "        rh02_lota,                                                                                           ";
    $sSql .= "        0,                                                                                                   ";
    $sSql .= "        rh141_instit                                                                                         ";
    $sSql .= "        from (                                                                                               ";
    $sSql .= "          select rh141_anousu,                                                                               ";
    $sSql .= "                 rh141_mesusu,                                                                               ";
    $sSql .= "                 rh143_regist,                                                                               ";
    $sSql .= "                 rh143_rubrica,                                                                              ";
    $sSql .= "                 sum(rh143_valor) as rh143_valor,                                                            ";
    $sSql .= "                 rh143_tipoevento,                                                                           ";
    $sSql .= "                 max(rh143_quantidade) as rh143_quantidade,                                                  ";
    $sSql .= "                 rh141_instit                                                                                ";
    $sSql .= "            from rhfolhapagamento                                                                            ";
    $sSql .= "                 inner join rhhistoricocalculo on rh141_sequencial = rh143_folhapagamento                    ";
    $sSql .= "           where rh141_anousu     = {$iAno}                                                                  ";
    $sSql .= "             and rh141_mesusu     = {$iMes}                                                                  ";
    $sSql .= "             and rh141_tipofolha  = 3                                                                        ";
    $sSql .= "           group by rh141_anousu, rh141_mesusu, rh143_regist, rh143_rubrica, rh143_tipoevento, rh141_instit  ";
    $sSql .= "             ) as base                                                                                       ";
    $sSql .= "             inner join rhpessoalmov on rh02_anousu      = base.rh141_anousu                                 ";
    $sSql .= "                                    and rh02_mesusu      = rh141_mesusu                                      ";
    $sSql .= "                                    and rh02_regist      = rh143_regist                                      ";
    $sSql .= "                                    and rh02_instit      = rh141_instit;                                     ";

    return $sSql;
  }

  public function migraGerfCom($iInstituicao) {

    $sSql  = "create table w_migracao_rhfolhapagamento as                                                                                                "; 
    $sSql .= "select distinct r48_anousu,                                                                                                                ";
    $sSql .= "                r48_mesusu,                                                                                                                ";
    $sSql .= "                r48_semest,                                                                                                                ";
    $sSql .= "                r48_instit                                                                                                                 ";
    $sSql .= "  from gerfcom                                                                                                                             ";
    $sSql .= "     inner join pontocom on r47_regist = r48_regist                                                                                        ";
    $sSql .= "                        and r47_anousu = r48_anousu                                                                                        ";
    $sSql .= "                        and r47_mesusu = r48_mesusu                                                                                        ";
    $sSql .= "                        and r47_instit = {$iInstituicao}                                                                                   ";
    $sSql .= "order by r48_anousu asc,                                                                                                                   ";
    $sSql .= "         r48_mesusu asc,                                                                                                                   ";
    $sSql .= "         r48_semest asc;                                                                                                                   ";
                                                                                                                                                         
    $sSql .= "  insert into rhfolhapagamento                                                                                                             ";
    $sSql .= "select nextval('rhfolhapagamento_rh141_sequencial_seq'),                                                                                   ";
    $sSql .= "       r48_semest,                                                                                                                         ";
    $sSql .= "       r48_anousu,                                                                                                                         ";
    $sSql .= "       r48_mesusu,                                                                                                                         ";
    $sSql .= "       r48_anousu,                                                                                                                         ";
    $sSql .= "       r48_mesusu,                                                                                                                         ";
    $sSql .= "       r48_instit,                                                                                                                         ";
    $sSql .= "       3,                                                                                                                                  ";
    $sSql .= "       false,                                                                                                                              ";
    $sSql .= "       'Folha complementar número: ' || r48_semest || ' da competência: ' || r48_anousu || '/' || r48_mesusu || ' gerada automaticamente.' ";
    $sSql .= "  from w_migracao_rhfolhapagamento                                                                                                         ";
    $sSql .= "order by r48_anousu asc,                                                                                                                   ";
    $sSql .= "          r48_mesusu asc,                                                                                                                  ";
    $sSql .= "          r48_semest asc;                                                                                                                  ";
                                                                                                                                                         
    $sSql .= "    create table w_ultimafolhadecadacompetencia as                                                                                         ";
    $sSql .= "select max(rh141_codigo) as ultimafolha,                                                                                                   ";
    $sSql .= "                           rh141_anousu,                                                                                                   ";
    $sSql .= "                           rh141_mesusu,                                                                                                   ";
    $sSql .= "                           rh141_instit                                                                                                    ";
    $sSql .= "  from rhfolhapagamento                                                                                                                    ";
    $sSql .= "group by rh141_anousu,rh141_mesusu, rh141_instit;                                                                                          ";
                                                                                                                                                         
    $sSql .= "insert into rhhistoricoponto                                                                                                               ";
    $sSql .= "  (rh144_sequencial,rh144_regist,rh144_folhapagamento,rh144_rubrica,rh144_quantidade,rh144_valor)                                          ";
    $sSql .= "select nextval('rhhistoricoponto_rh144_sequencial_seq'),                                                                                   ";
    $sSql .= "       r47_regist,                                                                                                                         ";
    $sSql .= "       rhfolhapagamento.rh141_sequencial,                                                                                                  ";
    $sSql .= "       r47_rubric,                                                                                                                         ";
    $sSql .= "       r47_quant,                                                                                                                          ";
    $sSql .= "       r47_valor                                                                                                                           ";
    $sSql .= "  from pontocom                                                                                                                            ";
    $sSql .= "  inner join w_ultimafolhadecadacompetencia on w_ultimafolhadecadacompetencia.rh141_anousu = r47_anousu                                    ";
    $sSql .= "                                           and w_ultimafolhadecadacompetencia.rh141_mesusu = r47_mesusu                                    ";
    $sSql .= "                                           and w_ultimafolhadecadacompetencia.rh141_instit = r47_instit                                    ";
    $sSql .= "  inner join rhfolhapagamento               on w_ultimafolhadecadacompetencia.rh141_mesusu = rhfolhapagamento.rh141_mesusu                 ";
    $sSql .= "                                           and w_ultimafolhadecadacompetencia.rh141_anousu = rhfolhapagamento.rh141_anousu                 ";
    $sSql .= "                                           and w_ultimafolhadecadacompetencia.rh141_instit = rhfolhapagamento.rh141_instit                 ";
    $sSql .= "                                           and w_ultimafolhadecadacompetencia.ultimafolha  = rhfolhapagamento.rh141_codigo                 ";
    $sSql .= "order by rh141_sequencial;                                                                                                                 ";
                                                                                                                                                         
    $sSql .= "insert into rhhistoricocalculo                                                                                                             ";
    $sSql .= "select nextval('rhhistoricocalculo_rh143_sequencial_seq'),                                                                                 ";
    $sSql .= "       r48_regist,                                                                                                                         ";
    $sSql .= "       rhfolhapagamento.rh141_sequencial,                                                                                                  ";
    $sSql .= "       r48_rubric,                                                                                                                         ";
    $sSql .= "       r48_quant,                                                                                                                          ";
    $sSql .= "       r48_valor,                                                                                                                          ";
    $sSql .= "       r48_pd                                                                                                                              ";
    $sSql .= "  from gerfcom                                                                                                                             ";
    $sSql .= "inner join rhfolhapagamento                 on  r48_anousu      = rh141_anousu                                                             ";
    $sSql .= "                                           and  r48_mesusu      = rh141_mesusu                                                             ";
    $sSql .= "                                           and  r48_instit      = rh141_instit                                                             ";
    $sSql .= "                                           and  rh141_codigo    = r48_semest                                                               ";
    $sSql .= "                                           and  rh141_tipofolha = 3                                                                        ";
    $sSql .= "order by rh141_sequencial;                                                                                                                 ";
                                                                                                                                                                                                                               

    return $sSql;
  }
}
