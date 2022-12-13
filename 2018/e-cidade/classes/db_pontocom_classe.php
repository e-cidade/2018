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
//CLASSE DA ENTIDADE pontocom
class cl_pontocom { 
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
   var $r47_anousu = 0; 
   var $r47_mesusu = 0; 
   var $r47_regist = 0; 
   var $r47_rubric = null; 
   var $r47_valor = 0; 
   var $r47_quant = 0; 
   var $r47_lotac = null; 
   var $r47_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r47_anousu = int4 = Ano do Exercicio 
                 r47_mesusu = int4 = Mes do Exercicio 
                 r47_regist = int4 = Codigo do Funcionario 
                 r47_rubric = char(     4) = Codigo da Rubrica 
                 r47_valor = float8 = Valor (utilizado como work) 
                 r47_quant = float8 = Qtda ou Valor para inicializar 
                 r47_lotac = char(4) = Lotacao do Funcionario 
                 r47_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_pontocom() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pontocom"); 
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
       $this->r47_anousu = ($this->r47_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r47_anousu"]:$this->r47_anousu);
       $this->r47_mesusu = ($this->r47_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r47_mesusu"]:$this->r47_mesusu);
       $this->r47_regist = ($this->r47_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r47_regist"]:$this->r47_regist);
       $this->r47_rubric = ($this->r47_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r47_rubric"]:$this->r47_rubric);
       $this->r47_valor = ($this->r47_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r47_valor"]:$this->r47_valor);
       $this->r47_quant = ($this->r47_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r47_quant"]:$this->r47_quant);
       $this->r47_lotac = ($this->r47_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r47_lotac"]:$this->r47_lotac);
       $this->r47_instit = ($this->r47_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r47_instit"]:$this->r47_instit);
     }else{
       $this->r47_anousu = ($this->r47_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r47_anousu"]:$this->r47_anousu);
       $this->r47_mesusu = ($this->r47_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r47_mesusu"]:$this->r47_mesusu);
       $this->r47_regist = ($this->r47_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r47_regist"]:$this->r47_regist);
       $this->r47_rubric = ($this->r47_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r47_rubric"]:$this->r47_rubric);
     }
   }
   // funcao para inclusao
   function incluir ($r47_anousu,$r47_mesusu,$r47_regist,$r47_rubric){ 
      $this->atualizacampos();
     if($this->r47_valor == null ){ 
       $this->erro_sql = " Campo Valor (utilizado como work) não informado.";
       $this->erro_campo = "r47_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r47_quant == null ){ 
       $this->erro_sql = " Campo Qtda ou Valor para inicializar não informado.";
       $this->erro_campo = "r47_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r47_lotac == null ){ 
       $this->erro_sql = " Campo Lotacao do Funcionario não informado.";
       $this->erro_campo = "r47_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r47_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao não informado.";
       $this->erro_campo = "r47_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r47_anousu = $r47_anousu; 
       $this->r47_mesusu = $r47_mesusu; 
       $this->r47_regist = $r47_regist; 
       $this->r47_rubric = $r47_rubric; 
     if(($this->r47_anousu == null) || ($this->r47_anousu == "") ){ 
       $this->erro_sql = " Campo r47_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r47_mesusu == null) || ($this->r47_mesusu == "") ){ 
       $this->erro_sql = " Campo r47_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r47_regist == null) || ($this->r47_regist == "") ){ 
       $this->erro_sql = " Campo r47_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r47_rubric == null) || ($this->r47_rubric == "") ){ 
       $this->erro_sql = " Campo r47_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pontocom(
                                       r47_anousu 
                                      ,r47_mesusu 
                                      ,r47_regist 
                                      ,r47_rubric 
                                      ,r47_valor 
                                      ,r47_quant 
                                      ,r47_lotac 
                                      ,r47_instit 
                       )
                values (
                                $this->r47_anousu 
                               ,$this->r47_mesusu 
                               ,$this->r47_regist 
                               ,'$this->r47_rubric' 
                               ,$this->r47_valor 
                               ,$this->r47_quant 
                               ,'$this->r47_lotac' 
                               ,$this->r47_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ponto complementar ($this->r47_anousu."-".$this->r47_mesusu."-".$this->r47_regist."-".$this->r47_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ponto complementar já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ponto complementar ($this->r47_anousu."-".$this->r47_mesusu."-".$this->r47_regist."-".$this->r47_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r47_anousu."-".$this->r47_mesusu."-".$this->r47_regist."-".$this->r47_rubric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r47_anousu,$this->r47_mesusu,$this->r47_regist,$this->r47_rubric  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4274,'$this->r47_anousu','I')");
         $resac = db_query("insert into db_acountkey values($acount,4275,'$this->r47_mesusu','I')");
         $resac = db_query("insert into db_acountkey values($acount,4276,'$this->r47_regist','I')");
         $resac = db_query("insert into db_acountkey values($acount,4277,'$this->r47_rubric','I')");
         $resac = db_query("insert into db_acount values($acount,574,4274,'','".AddSlashes(pg_result($resaco,0,'r47_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,574,4275,'','".AddSlashes(pg_result($resaco,0,'r47_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,574,4276,'','".AddSlashes(pg_result($resaco,0,'r47_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,574,4277,'','".AddSlashes(pg_result($resaco,0,'r47_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,574,4278,'','".AddSlashes(pg_result($resaco,0,'r47_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,574,4279,'','".AddSlashes(pg_result($resaco,0,'r47_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,574,4280,'','".AddSlashes(pg_result($resaco,0,'r47_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,574,7461,'','".AddSlashes(pg_result($resaco,0,'r47_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($r47_anousu=null,$r47_mesusu=null,$r47_regist=null,$r47_rubric=null) { 
      $this->atualizacampos();
     $sql = " update pontocom set ";
     $virgula = "";
     if(trim($this->r47_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r47_anousu"])){ 
       $sql  .= $virgula." r47_anousu = $this->r47_anousu ";
       $virgula = ",";
       if(trim($this->r47_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio não informado.";
         $this->erro_campo = "r47_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r47_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r47_mesusu"])){ 
       $sql  .= $virgula." r47_mesusu = $this->r47_mesusu ";
       $virgula = ",";
       if(trim($this->r47_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio não informado.";
         $this->erro_campo = "r47_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r47_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r47_regist"])){ 
       $sql  .= $virgula." r47_regist = $this->r47_regist ";
       $virgula = ",";
       if(trim($this->r47_regist) == null ){ 
         $this->erro_sql = " Campo Codigo do Funcionario não informado.";
         $this->erro_campo = "r47_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r47_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r47_rubric"])){ 
       $sql  .= $virgula." r47_rubric = '$this->r47_rubric' ";
       $virgula = ",";
       if(trim($this->r47_rubric) == null ){ 
         $this->erro_sql = " Campo Codigo da Rubrica não informado.";
         $this->erro_campo = "r47_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r47_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r47_valor"])){ 
       $sql  .= $virgula." r47_valor = $this->r47_valor ";
       $virgula = ",";
       if(trim($this->r47_valor) == null ){ 
         $this->erro_sql = " Campo Valor (utilizado como work) não informado.";
         $this->erro_campo = "r47_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r47_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r47_quant"])){ 
       $sql  .= $virgula." r47_quant = $this->r47_quant ";
       $virgula = ",";
       if(trim($this->r47_quant) == null ){ 
         $this->erro_sql = " Campo Qtda ou Valor para inicializar não informado.";
         $this->erro_campo = "r47_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r47_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r47_lotac"])){ 
       $sql  .= $virgula." r47_lotac = '$this->r47_lotac' ";
       $virgula = ",";
       if(trim($this->r47_lotac) == null ){ 
         $this->erro_sql = " Campo Lotacao do Funcionario não informado.";
         $this->erro_campo = "r47_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r47_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r47_instit"])){ 
       $sql  .= $virgula." r47_instit = $this->r47_instit ";
       $virgula = ",";
       if(trim($this->r47_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao não informado.";
         $this->erro_campo = "r47_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r47_anousu!=null){
       $sql .= " r47_anousu = $this->r47_anousu";
     }
     if($r47_mesusu!=null){
       $sql .= " and  r47_mesusu = $this->r47_mesusu";
     }
     if($r47_regist!=null){
       $sql .= " and  r47_regist = $this->r47_regist";
     }
     if($r47_rubric!=null){
       $sql .= " and  r47_rubric = '$this->r47_rubric'";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r47_anousu,$this->r47_mesusu,$this->r47_regist,$this->r47_rubric));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,4274,'$this->r47_anousu','A')");
           $resac = db_query("insert into db_acountkey values($acount,4275,'$this->r47_mesusu','A')");
           $resac = db_query("insert into db_acountkey values($acount,4276,'$this->r47_regist','A')");
           $resac = db_query("insert into db_acountkey values($acount,4277,'$this->r47_rubric','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r47_anousu"]) || $this->r47_anousu != "")
             $resac = db_query("insert into db_acount values($acount,574,4274,'".AddSlashes(pg_result($resaco,$conresaco,'r47_anousu'))."','$this->r47_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r47_mesusu"]) || $this->r47_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,574,4275,'".AddSlashes(pg_result($resaco,$conresaco,'r47_mesusu'))."','$this->r47_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r47_regist"]) || $this->r47_regist != "")
             $resac = db_query("insert into db_acount values($acount,574,4276,'".AddSlashes(pg_result($resaco,$conresaco,'r47_regist'))."','$this->r47_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r47_rubric"]) || $this->r47_rubric != "")
             $resac = db_query("insert into db_acount values($acount,574,4277,'".AddSlashes(pg_result($resaco,$conresaco,'r47_rubric'))."','$this->r47_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r47_valor"]) || $this->r47_valor != "")
             $resac = db_query("insert into db_acount values($acount,574,4278,'".AddSlashes(pg_result($resaco,$conresaco,'r47_valor'))."','$this->r47_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r47_quant"]) || $this->r47_quant != "")
             $resac = db_query("insert into db_acount values($acount,574,4279,'".AddSlashes(pg_result($resaco,$conresaco,'r47_quant'))."','$this->r47_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r47_lotac"]) || $this->r47_lotac != "")
             $resac = db_query("insert into db_acount values($acount,574,4280,'".AddSlashes(pg_result($resaco,$conresaco,'r47_lotac'))."','$this->r47_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r47_instit"]) || $this->r47_instit != "")
             $resac = db_query("insert into db_acount values($acount,574,7461,'".AddSlashes(pg_result($resaco,$conresaco,'r47_instit'))."','$this->r47_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ponto complementar nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r47_anousu."-".$this->r47_mesusu."-".$this->r47_regist."-".$this->r47_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Ponto complementar nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r47_anousu."-".$this->r47_mesusu."-".$this->r47_regist."-".$this->r47_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r47_anousu."-".$this->r47_mesusu."-".$this->r47_regist."-".$this->r47_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($r47_anousu=null,$r47_mesusu=null,$r47_regist=null,$r47_rubric=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($r47_anousu,$r47_mesusu,$r47_regist,$r47_rubric));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,4274,'$r47_anousu','E')");
           $resac  = db_query("insert into db_acountkey values($acount,4275,'$r47_mesusu','E')");
           $resac  = db_query("insert into db_acountkey values($acount,4276,'$r47_regist','E')");
           $resac  = db_query("insert into db_acountkey values($acount,4277,'$r47_rubric','E')");
           $resac  = db_query("insert into db_acount values($acount,574,4274,'','".AddSlashes(pg_result($resaco,$iresaco,'r47_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,574,4275,'','".AddSlashes(pg_result($resaco,$iresaco,'r47_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,574,4276,'','".AddSlashes(pg_result($resaco,$iresaco,'r47_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,574,4277,'','".AddSlashes(pg_result($resaco,$iresaco,'r47_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,574,4278,'','".AddSlashes(pg_result($resaco,$iresaco,'r47_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,574,4279,'','".AddSlashes(pg_result($resaco,$iresaco,'r47_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,574,4280,'','".AddSlashes(pg_result($resaco,$iresaco,'r47_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,574,7461,'','".AddSlashes(pg_result($resaco,$iresaco,'r47_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from pontocom
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($r47_anousu)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r47_anousu = $r47_anousu ";
        }
        if (!empty($r47_mesusu)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r47_mesusu = $r47_mesusu ";
        }
        if (!empty($r47_regist)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r47_regist = $r47_regist ";
        }
        if (!empty($r47_rubric)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r47_rubric = '$r47_rubric' ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ponto complementar nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r47_anousu."-".$r47_mesusu."-".$r47_regist."-".$r47_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Ponto complementar nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r47_anousu."-".$r47_mesusu."-".$r47_regist."-".$r47_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r47_anousu."-".$r47_mesusu."-".$r47_regist."-".$r47_rubric;
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
        $this->erro_sql   = "Record Vazio na Tabela:pontocom";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($r47_anousu = null,$r47_mesusu = null,$r47_regist = null,$r47_rubric = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from pontocom ";
     $sql .= "      inner join db_config  on  db_config.codigo = pontocom.r47_instit";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = pontocom.r47_anousu and  lotacao.r13_mesusu = pontocom.r47_mesusu and  lotacao.r13_codigo = pontocom.r47_lotac";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = pontocom.r47_anousu and  pessoal.r01_mesusu = pontocom.r47_mesusu and  pessoal.r01_regist = pontocom.r47_regist";
     $sql .= "      inner join rubricas  on  rubricas.r06_anousu = pontocom.r47_anousu and  rubricas.r06_mesusu = pontocom.r47_mesusu and  rubricas.r06_codigo = pontocom.r47_rubric";
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
       if (!empty($r47_anousu)) {
         $sql2 .= " where pontocom.r47_anousu = $r47_anousu "; 
       } 
       if (!empty($r47_mesusu)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " pontocom.r47_mesusu = $r47_mesusu "; 
       } 
       if (!empty($r47_regist)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " pontocom.r47_regist = $r47_regist "; 
       } 
       if (!empty($r47_rubric)) {
         if (!empty($sql2)) {
            $sql2 .= " and ";
         } else {
            $sql2 .= " where ";
         } 
         $sql2 .= " pontocom.r47_rubric = '$r47_rubric' "; 
       } 
     } else if (!empty($dbwhere)) {
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
  function sql_query_file ( $r47_anousu=null,$r47_mesusu=null,$r47_regist=null,$r47_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontocom ";
     $sql2 = "";
     if($dbwhere==""){
       if($r47_anousu!=null ){
         $sql2 .= " where pontocom.r47_anousu = $r47_anousu "; 
       } 
       if($r47_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontocom.r47_mesusu = $r47_mesusu "; 
       } 
       if($r47_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontocom.r47_regist = $r47_regist "; 
       } 
       if($r47_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontocom.r47_rubric = '$r47_rubric' "; 
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

   function sql_query_seleciona ( $r47_anousu=null,$r47_mesusu=null,$r47_regist=null,$r47_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontocom ";
     $sql .= "      inner join rhpessoal    on  rhpessoal.rh01_regist = pontocom.r47_regist";
     $sql .= "      inner join rhpessoalmov on  rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist ";
     $sql .= "                             and  pontocom.r47_anousu          = rhpessoalmov.rh02_anousu ";
     $sql .= "                             and  pontocom.r47_mesusu          = rhpessoalmov.rh02_mesusu ";
     $sql .= "                             and  pontocom.r47_instit          = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join rhfuncao     on  rhpessoalmov.rh02_funcao    = rhfuncao.rh37_funcao     ";
     $sql .= "                             and  rhpessoalmov.rh02_instit    = rhfuncao.rh37_instit     ";
     $sql .= "      inner join rhregime     on  rhpessoalmov.rh02_codreg    = rhregime.rh30_codreg     ";
     $sql .= "      inner join rhrubricas   on  rhrubricas.rh27_rubric = pontocom.r47_rubric
		                                       and  rhrubricas.rh27_instit = pontocom.r47_instit ";
     $sql .= "      inner join rhlota       on  rhlota.r70_codigo::char(12) = pontocom.r47_lotac
		                                       and  rhlota.r70_instit =  pontocom.r47_instit ";
     $sql .= "      inner join cgm          on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r47_anousu!=null ){
         $sql2 .= " where pontocom.r47_anousu = $r47_anousu "; 
       } 
       if($r47_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontocom.r47_mesusu = $r47_mesusu "; 
       } 
       if($r47_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontocom.r47_regist = $r47_regist "; 
       } 
       if($r47_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontocom.r47_rubric = '$r47_rubric' "; 
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
