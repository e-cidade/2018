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
//CLASSE DA ENTIDADE pontofs
class cl_pontofs { 
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
   var $r10_anousu = 0; 
   var $r10_mesusu = 0; 
   var $r10_regist = 0; 
   var $r10_rubric = null; 
   var $r10_valor = 0; 
   var $r10_quant = 0; 
   var $r10_lotac = null; 
   var $r10_datlim = null; 
   var $r10_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r10_anousu = int4 = Ano do Exercicio 
                 r10_mesusu = int4 = Mes do Exercicio 
                 r10_regist = int4 = Matrícula 
                 r10_rubric = char(4) = Rubrica 
                 r10_valor = float8 = Valor 
                 r10_quant = float8 = Quantidade 
                 r10_lotac = char(4) = Lotação 
                 r10_datlim = char(7) = Ano/Mês 
                 r10_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_pontofs() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pontofs"); 
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
       $this->r10_anousu = ($this->r10_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r10_anousu"]:$this->r10_anousu);
       $this->r10_mesusu = ($this->r10_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r10_mesusu"]:$this->r10_mesusu);
       $this->r10_regist = ($this->r10_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r10_regist"]:$this->r10_regist);
       $this->r10_rubric = ($this->r10_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r10_rubric"]:$this->r10_rubric);
       $this->r10_valor = ($this->r10_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r10_valor"]:$this->r10_valor);
       $this->r10_quant = ($this->r10_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r10_quant"]:$this->r10_quant);
       $this->r10_lotac = ($this->r10_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r10_lotac"]:$this->r10_lotac);
       $this->r10_datlim = ($this->r10_datlim == ""?@$GLOBALS["HTTP_POST_VARS"]["r10_datlim"]:$this->r10_datlim);
       $this->r10_instit = ($this->r10_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r10_instit"]:$this->r10_instit);
     }else{
       $this->r10_anousu = ($this->r10_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r10_anousu"]:$this->r10_anousu);
       $this->r10_mesusu = ($this->r10_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r10_mesusu"]:$this->r10_mesusu);
       $this->r10_regist = ($this->r10_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r10_regist"]:$this->r10_regist);
       $this->r10_rubric = ($this->r10_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r10_rubric"]:$this->r10_rubric);
     }
   }
   // funcao para inclusao
   function incluir ($r10_anousu,$r10_mesusu,$r10_regist,$r10_rubric){ 
      $this->atualizacampos();
     if($this->r10_valor == null ){ 
       $this->erro_sql = " Campo Valor não informado.";
       $this->erro_campo = "r10_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r10_quant == null ){ 
       $this->erro_sql = " Campo Quantidade não informado.";
       $this->erro_campo = "r10_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r10_lotac == null ){ 
       $this->erro_sql = " Campo Lotação não informado.";
       $this->erro_campo = "r10_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r10_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao não informado.";
       $this->erro_campo = "r10_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r10_anousu = $r10_anousu; 
       $this->r10_mesusu = $r10_mesusu; 
       $this->r10_regist = $r10_regist; 
       $this->r10_rubric = $r10_rubric; 
     if(($this->r10_anousu == null) || ($this->r10_anousu == "") ){ 
       $this->erro_sql = " Campo r10_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r10_mesusu == null) || ($this->r10_mesusu == "") ){ 
       $this->erro_sql = " Campo r10_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r10_regist == null) || ($this->r10_regist == "") ){ 
       $this->erro_sql = " Campo r10_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r10_rubric == null) || ($this->r10_rubric == "") ){ 
       $this->erro_sql = " Campo r10_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pontofs(
                                       r10_anousu 
                                      ,r10_mesusu 
                                      ,r10_regist 
                                      ,r10_rubric 
                                      ,r10_valor 
                                      ,r10_quant 
                                      ,r10_lotac 
                                      ,r10_datlim 
                                      ,r10_instit 
                       )
                values (
                                $this->r10_anousu 
                               ,$this->r10_mesusu 
                               ,$this->r10_regist 
                               ,'$this->r10_rubric' 
                               ,$this->r10_valor 
                               ,$this->r10_quant 
                               ,'$this->r10_lotac' 
                               ,'$this->r10_datlim' 
                               ,$this->r10_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ponto de Salario ($this->r10_anousu."-".$this->r10_mesusu."-".$this->r10_regist."-".$this->r10_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ponto de Salario já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ponto de Salario ($this->r10_anousu."-".$this->r10_mesusu."-".$this->r10_regist."-".$this->r10_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r10_anousu."-".$this->r10_mesusu."-".$this->r10_regist."-".$this->r10_rubric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r10_anousu,$this->r10_mesusu,$this->r10_regist,$this->r10_rubric  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4315,'$this->r10_anousu','I')");
         $resac = db_query("insert into db_acountkey values($acount,4316,'$this->r10_mesusu','I')");
         $resac = db_query("insert into db_acountkey values($acount,4317,'$this->r10_regist','I')");
         $resac = db_query("insert into db_acountkey values($acount,4318,'$this->r10_rubric','I')");
         $resac = db_query("insert into db_acount values($acount,579,4315,'','".AddSlashes(pg_result($resaco,0,'r10_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,579,4316,'','".AddSlashes(pg_result($resaco,0,'r10_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,579,4317,'','".AddSlashes(pg_result($resaco,0,'r10_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,579,4318,'','".AddSlashes(pg_result($resaco,0,'r10_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,579,4319,'','".AddSlashes(pg_result($resaco,0,'r10_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,579,4320,'','".AddSlashes(pg_result($resaco,0,'r10_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,579,4321,'','".AddSlashes(pg_result($resaco,0,'r10_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,579,4322,'','".AddSlashes(pg_result($resaco,0,'r10_datlim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,579,7466,'','".AddSlashes(pg_result($resaco,0,'r10_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($r10_anousu=null,$r10_mesusu=null,$r10_regist=null,$r10_rubric=null, $where="") { 
      $this->atualizacampos();
     $sql = " update pontofs set ";
     $virgula = "";
     if(trim($this->r10_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r10_anousu"])){ 
       $sql  .= $virgula." r10_anousu = $this->r10_anousu ";
       $virgula = ",";
       if(trim($this->r10_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio não informado.";
         $this->erro_campo = "r10_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r10_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r10_mesusu"])){ 
       $sql  .= $virgula." r10_mesusu = $this->r10_mesusu ";
       $virgula = ",";
       if(trim($this->r10_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio não informado.";
         $this->erro_campo = "r10_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r10_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r10_regist"])){ 
       $sql  .= $virgula." r10_regist = $this->r10_regist ";
       $virgula = ",";
       if(trim($this->r10_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula não informado.";
         $this->erro_campo = "r10_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r10_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r10_rubric"])){ 
       $sql  .= $virgula." r10_rubric = '$this->r10_rubric' ";
       $virgula = ",";
       if(trim($this->r10_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica não informado.";
         $this->erro_campo = "r10_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r10_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r10_valor"])){ 
       $sql  .= $virgula." r10_valor = $this->r10_valor ";
       $virgula = ",";
       if(trim($this->r10_valor) == null ){ 
         $this->erro_sql = " Campo Valor não informado.";
         $this->erro_campo = "r10_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r10_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r10_quant"])){ 
       $sql  .= $virgula." r10_quant = $this->r10_quant ";
       $virgula = ",";
       if(trim($this->r10_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade não informado.";
         $this->erro_campo = "r10_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r10_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r10_lotac"])){ 
       $sql  .= $virgula." r10_lotac = '$this->r10_lotac' ";
       $virgula = ",";
       if(trim($this->r10_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação não informado.";
         $this->erro_campo = "r10_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r10_datlim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r10_datlim"])){ 
       $sql  .= $virgula." r10_datlim = '$this->r10_datlim' ";
       $virgula = ",";
     }
     if(trim($this->r10_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r10_instit"])){ 
       $sql  .= $virgula." r10_instit = $this->r10_instit ";
       $virgula = ",";
       if(trim($this->r10_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao não informado.";
         $this->erro_campo = "r10_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r10_anousu!=null){
       $sql .= " r10_anousu = $this->r10_anousu";
     }
     if($r10_mesusu!=null){
       $sql .= " and  r10_mesusu = $this->r10_mesusu";
     }
     if($r10_regist!=null){
       $sql .= " and  r10_regist = $this->r10_regist";
     }
     if($r10_rubric!=null){
       $sql .= " and  r10_rubric = '$this->r10_rubric'";
     }
     if(trim($where) != ""){
       if(strpos("where",$sql) != ""){
         $sql .= " and ";
       }
       $sql .= $where;
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->r10_anousu,$this->r10_mesusu,$this->r10_regist,$this->r10_rubric));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,4315,'$this->r10_anousu','A')");
           $resac = db_query("insert into db_acountkey values($acount,4316,'$this->r10_mesusu','A')");
           $resac = db_query("insert into db_acountkey values($acount,4317,'$this->r10_regist','A')");
           $resac = db_query("insert into db_acountkey values($acount,4318,'$this->r10_rubric','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r10_anousu"]) || $this->r10_anousu != "")
             $resac = db_query("insert into db_acount values($acount,579,4315,'".AddSlashes(pg_result($resaco,$conresaco,'r10_anousu'))."','$this->r10_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r10_mesusu"]) || $this->r10_mesusu != "")
             $resac = db_query("insert into db_acount values($acount,579,4316,'".AddSlashes(pg_result($resaco,$conresaco,'r10_mesusu'))."','$this->r10_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r10_regist"]) || $this->r10_regist != "")
             $resac = db_query("insert into db_acount values($acount,579,4317,'".AddSlashes(pg_result($resaco,$conresaco,'r10_regist'))."','$this->r10_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r10_rubric"]) || $this->r10_rubric != "")
             $resac = db_query("insert into db_acount values($acount,579,4318,'".AddSlashes(pg_result($resaco,$conresaco,'r10_rubric'))."','$this->r10_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r10_valor"]) || $this->r10_valor != "")
             $resac = db_query("insert into db_acount values($acount,579,4319,'".AddSlashes(pg_result($resaco,$conresaco,'r10_valor'))."','$this->r10_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r10_quant"]) || $this->r10_quant != "")
             $resac = db_query("insert into db_acount values($acount,579,4320,'".AddSlashes(pg_result($resaco,$conresaco,'r10_quant'))."','$this->r10_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r10_lotac"]) || $this->r10_lotac != "")
             $resac = db_query("insert into db_acount values($acount,579,4321,'".AddSlashes(pg_result($resaco,$conresaco,'r10_lotac'))."','$this->r10_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r10_datlim"]) || $this->r10_datlim != "")
             $resac = db_query("insert into db_acount values($acount,579,4322,'".AddSlashes(pg_result($resaco,$conresaco,'r10_datlim'))."','$this->r10_datlim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["r10_instit"]) || $this->r10_instit != "")
             $resac = db_query("insert into db_acount values($acount,579,7466,'".AddSlashes(pg_result($resaco,$conresaco,'r10_instit'))."','$this->r10_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }

     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ponto de Salario nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r10_anousu."-".$this->r10_mesusu."-".$this->r10_regist."-".$this->r10_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Ponto de Salario nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r10_anousu."-".$this->r10_mesusu."-".$this->r10_regist."-".$this->r10_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r10_anousu."-".$this->r10_mesusu."-".$this->r10_regist."-".$this->r10_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($r10_anousu=null,$r10_mesusu=null,$r10_regist=null,$r10_rubric=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($r10_anousu,$r10_mesusu,$r10_regist,$r10_rubric));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,4315,'$r10_anousu','E')");
           $resac  = db_query("insert into db_acountkey values($acount,4316,'$r10_mesusu','E')");
           $resac  = db_query("insert into db_acountkey values($acount,4317,'$r10_regist','E')");
           $resac  = db_query("insert into db_acountkey values($acount,4318,'$r10_rubric','E')");
           $resac  = db_query("insert into db_acount values($acount,579,4315,'','".AddSlashes(pg_result($resaco,$iresaco,'r10_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,579,4316,'','".AddSlashes(pg_result($resaco,$iresaco,'r10_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,579,4317,'','".AddSlashes(pg_result($resaco,$iresaco,'r10_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,579,4318,'','".AddSlashes(pg_result($resaco,$iresaco,'r10_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,579,4319,'','".AddSlashes(pg_result($resaco,$iresaco,'r10_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,579,4320,'','".AddSlashes(pg_result($resaco,$iresaco,'r10_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,579,4321,'','".AddSlashes(pg_result($resaco,$iresaco,'r10_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,579,4322,'','".AddSlashes(pg_result($resaco,$iresaco,'r10_datlim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,579,7466,'','".AddSlashes(pg_result($resaco,$iresaco,'r10_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from pontofs
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($r10_anousu)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r10_anousu = $r10_anousu ";
        }
        if (!empty($r10_mesusu)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r10_mesusu = $r10_mesusu ";
        }
        if (!empty($r10_regist)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r10_regist = $r10_regist ";
        }
        if (!empty($r10_rubric)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " r10_rubric = '$r10_rubric' ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ponto de Salario nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r10_anousu."-".$r10_mesusu."-".$r10_regist."-".$r10_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Ponto de Salario nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r10_anousu."-".$r10_mesusu."-".$r10_regist."-".$r10_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r10_anousu."-".$r10_mesusu."-".$r10_regist."-".$r10_rubric;
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
        $this->erro_sql   = "Record Vazio na Tabela:pontofs";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $r10_anousu=null,$r10_mesusu=null,$r10_regist=null,$r10_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontofs ";
     $sql .= "      inner join db_config  on  db_config.codigo = pontofs.r10_instit";
     $sql .= "      inner join lotacao  on  lotacao.r13_anousu = pontofs.r10_anousu 
		                                   and  lotacao.r13_mesusu = pontofs.r10_mesusu 
																			 and  lotacao.r13_codigo = pontofs.r10_lotac
																			 and  lotacao.r13_instit = pontofs.r10_instit ";
     $sql .= "      inner join pessoal  on  pessoal.r01_anousu = pontofs.r10_anousu 
		                                   and  pessoal.r01_mesusu = pontofs.r10_mesusu 
																			 and  pessoal.r01_regist = pontofs.r10_regist
																			 and  pessoal.r01_instit = pontofs.r10_instit ";
     $sql .= "      inner join rubricas  on  rubricas.r06_anousu = pontofs.r10_anousu 
		                                    and  rubricas.r06_mesusu = pontofs.r10_mesusu 
																				and  rubricas.r06_codigo = pontofs.r10_rubric
																				and  rubricas.r06_instit = pontofs.r10_instit ";
     $sql .= "      inner join cgm  as d on   d.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = pessoal.r01_instit";
     $sql .= "      inner join funcao  as d on d.r37_anousu = pessoal.r01_anousu 
		                                       and d.r37_mesusu = pessoal.r01_mesusu 
																					 and   d.r37_funcao = pessoal.r01_funcao
																					 and   d.r37_instit = pessoal.r01_instit ";
     $sql .= "      inner join inssirf  as d on d.r33_anousu = pessoal.r01_anousu 
		                                        and d.r33_mesusu = pessoal.r01_mesusu 
																						and d.r33_codtab = pessoal.r01_tbprev
																						and d.r33_instit = pessoal.r01_instit ";
     $sql .= "      inner join cargo  as d on d.r65_anousu = pessoal.r01_anousu 
		                                      and d.r65_mesusu = pessoal.r01_mesusu 
																					and d.r65_cargo = pessoal.r01_cargo";
     $sql2 = "";
     if($dbwhere==""){
       if($r10_anousu!=null ){
         $sql2 .= " where pontofs.r10_anousu = $r10_anousu "; 
       } 
       if($r10_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofs.r10_mesusu = $r10_mesusu "; 
       } 
       if($r10_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofs.r10_regist = $r10_regist "; 
       } 
       if($r10_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofs.r10_rubric = '$r10_rubric' "; 
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
    function sql_query_file ( $r10_anousu=null,$r10_mesusu=null,$r10_regist=null,$r10_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontofs ";
     $sql2 = "";
     if($dbwhere==""){
       if($r10_anousu!=null ){
         $sql2 .= " where pontofs.r10_anousu = $r10_anousu "; 
       } 
       if($r10_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofs.r10_mesusu = $r10_mesusu "; 
       } 
       if($r10_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofs.r10_regist = $r10_regist "; 
       } 
       if($r10_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofs.r10_rubric = '$r10_rubric' "; 
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

   function sql_query_seleciona ( $r10_anousu=null,$r10_mesusu=null,$r10_regist=null,$r10_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontofs ";
     $sql .= "      inner join rhpessoal    on  rhpessoal.rh01_regist       = pontofs.r10_regist       ";
     $sql .= "      inner join rhpessoalmov on  rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist ";
     $sql .= "                             and  pontofs.r10_anousu          = rhpessoalmov.rh02_anousu ";
     $sql .= "                             and  pontofs.r10_mesusu          = rhpessoalmov.rh02_mesusu ";
     $sql .= "                             and  pontofs.r10_instit          = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join rhfuncao     on  rhpessoalmov.rh02_funcao    = rhfuncao.rh37_funcao     ";
     $sql .= "                             and  rhpessoalmov.rh02_instit    = rhfuncao.rh37_instit     ";
     $sql .= "      inner join rhregime     on  rhpessoalmov.rh02_codreg    = rhregime.rh30_codreg     ";
     $sql .= "                             and  rhpessoalmov.rh02_instit    = rhregime.rh30_instit     ";
     $sql .= "      inner join rhrubricas   on  rhrubricas.rh27_rubric      = pontofs.r10_rubric       ";
		 $sql .= "                             and  rhrubricas.rh27_instit      = pontofs.r10_instit       ";
     $sql .= "      inner join rhlota       on  rhlota.r70_codigo::char(12) = pontofs.r10_lotac        ";
		 $sql .= "                             and  rhlota.r70_instit           = pontofs.r10_instit       ";
     $sql .= "      inner join cgm          on  cgm.z01_numcgm              = rhpessoal.rh01_numcgm    ";

     $sql2 = "";
     if($dbwhere==""){
       if($r10_anousu!=null ){
         $sql2 .= " where pontofs.r10_anousu = $r10_anousu "; 
       } 
       if($r10_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofs.r10_mesusu = $r10_mesusu "; 
       } 
       if($r10_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofs.r10_regist = $r10_regist "; 
       } 
       if($r10_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofs.r10_rubric = '$r10_rubric' "; 
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
