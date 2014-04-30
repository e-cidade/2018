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
//CLASSE DA ENTIDADE pontofa
class cl_pontofa { 
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
   var $r21_anousu = 0; 
   var $r21_mesusu = 0; 
   var $r21_regist = 0; 
   var $r21_rubric = null; 
   var $r21_valor = 0; 
   var $r21_quant = 0; 
   var $r21_lotac = null; 
   var $r21_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r21_anousu = int4 = Ano do Exercicio 
                 r21_mesusu = int4 = Mes do Exercicio 
                 r21_regist = int4 = Matrícula 
                 r21_rubric = char(4) = Rubrica 
                 r21_valor = float8 = Valor 
                 r21_quant = float8 = Quantidade 
                 r21_lotac = char(4) = Lotação 
                 r21_instit = int4 = codigo da instituicao 
                 ";
   //funcao construtor da classe 
   function cl_pontofa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pontofa"); 
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
       $this->r21_anousu = ($this->r21_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r21_anousu"]:$this->r21_anousu);
       $this->r21_mesusu = ($this->r21_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r21_mesusu"]:$this->r21_mesusu);
       $this->r21_regist = ($this->r21_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r21_regist"]:$this->r21_regist);
       $this->r21_rubric = ($this->r21_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r21_rubric"]:$this->r21_rubric);
       $this->r21_valor = ($this->r21_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r21_valor"]:$this->r21_valor);
       $this->r21_quant = ($this->r21_quant == ""?@$GLOBALS["HTTP_POST_VARS"]["r21_quant"]:$this->r21_quant);
       $this->r21_lotac = ($this->r21_lotac == ""?@$GLOBALS["HTTP_POST_VARS"]["r21_lotac"]:$this->r21_lotac);
       $this->r21_instit = ($this->r21_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["r21_instit"]:$this->r21_instit);
     }else{
       $this->r21_anousu = ($this->r21_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r21_anousu"]:$this->r21_anousu);
       $this->r21_mesusu = ($this->r21_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r21_mesusu"]:$this->r21_mesusu);
       $this->r21_regist = ($this->r21_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r21_regist"]:$this->r21_regist);
       $this->r21_rubric = ($this->r21_rubric == ""?@$GLOBALS["HTTP_POST_VARS"]["r21_rubric"]:$this->r21_rubric);
     }
   }
   // funcao para inclusao
   function incluir ($r21_anousu,$r21_mesusu,$r21_regist,$r21_rubric){ 
      $this->atualizacampos();
     if($this->r21_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "r21_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r21_quant == null ){ 
       $this->erro_sql = " Campo Quantidade nao Informado.";
       $this->erro_campo = "r21_quant";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r21_lotac == null ){ 
       $this->erro_sql = " Campo Lotação nao Informado.";
       $this->erro_campo = "r21_lotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r21_instit == null ){ 
       $this->erro_sql = " Campo codigo da instituicao nao Informado.";
       $this->erro_campo = "r21_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->r21_anousu = $r21_anousu; 
       $this->r21_mesusu = $r21_mesusu; 
       $this->r21_regist = $r21_regist; 
       $this->r21_rubric = $r21_rubric; 
     if(($this->r21_anousu == null) || ($this->r21_anousu == "") ){ 
       $this->erro_sql = " Campo r21_anousu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r21_mesusu == null) || ($this->r21_mesusu == "") ){ 
       $this->erro_sql = " Campo r21_mesusu nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r21_regist == null) || ($this->r21_regist == "") ){ 
       $this->erro_sql = " Campo r21_regist nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->r21_rubric == null) || ($this->r21_rubric == "") ){ 
       $this->erro_sql = " Campo r21_rubric nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pontofa(
                                       r21_anousu 
                                      ,r21_mesusu 
                                      ,r21_regist 
                                      ,r21_rubric 
                                      ,r21_valor 
                                      ,r21_quant 
                                      ,r21_lotac 
                                      ,r21_instit 
                       )
                values (
                                $this->r21_anousu 
                               ,$this->r21_mesusu 
                               ,$this->r21_regist 
                               ,'$this->r21_rubric' 
                               ,$this->r21_valor 
                               ,$this->r21_quant 
                               ,'$this->r21_lotac' 
                               ,$this->r21_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ponto de Adiantamento ($this->r21_anousu."-".$this->r21_mesusu."-".$this->r21_regist."-".$this->r21_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ponto de Adiantamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ponto de Adiantamento ($this->r21_anousu."-".$this->r21_mesusu."-".$this->r21_regist."-".$this->r21_rubric) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r21_anousu."-".$this->r21_mesusu."-".$this->r21_regist."-".$this->r21_rubric;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r21_anousu,$this->r21_mesusu,$this->r21_regist,$this->r21_rubric));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,4290,'$this->r21_anousu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4291,'$this->r21_mesusu','I')");
       $resac = db_query("insert into db_acountkey values($acount,4292,'$this->r21_regist','I')");
       $resac = db_query("insert into db_acountkey values($acount,4293,'$this->r21_rubric','I')");
       $resac = db_query("insert into db_acount values($acount,576,4290,'','".AddSlashes(pg_result($resaco,0,'r21_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,576,4291,'','".AddSlashes(pg_result($resaco,0,'r21_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,576,4292,'','".AddSlashes(pg_result($resaco,0,'r21_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,576,4293,'','".AddSlashes(pg_result($resaco,0,'r21_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,576,4294,'','".AddSlashes(pg_result($resaco,0,'r21_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,576,4295,'','".AddSlashes(pg_result($resaco,0,'r21_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,576,4296,'','".AddSlashes(pg_result($resaco,0,'r21_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,576,7463,'','".AddSlashes(pg_result($resaco,0,'r21_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r21_anousu=null,$r21_mesusu=null,$r21_regist=null,$r21_rubric=null,$where="") { 
      $this->atualizacampos();
     $sql = " update pontofa set ";
     $virgula = "";
     if(trim($this->r21_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r21_anousu"])){ 
       $sql  .= $virgula." r21_anousu = $this->r21_anousu ";
       $virgula = ",";
       if(trim($this->r21_anousu) == null ){ 
         $this->erro_sql = " Campo Ano do Exercicio nao Informado.";
         $this->erro_campo = "r21_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r21_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r21_mesusu"])){ 
       $sql  .= $virgula." r21_mesusu = $this->r21_mesusu ";
       $virgula = ",";
       if(trim($this->r21_mesusu) == null ){ 
         $this->erro_sql = " Campo Mes do Exercicio nao Informado.";
         $this->erro_campo = "r21_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r21_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r21_regist"])){ 
       $sql  .= $virgula." r21_regist = $this->r21_regist ";
       $virgula = ",";
       if(trim($this->r21_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "r21_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r21_rubric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r21_rubric"])){ 
       $sql  .= $virgula." r21_rubric = '$this->r21_rubric' ";
       $virgula = ",";
       if(trim($this->r21_rubric) == null ){ 
         $this->erro_sql = " Campo Rubrica nao Informado.";
         $this->erro_campo = "r21_rubric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r21_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r21_valor"])){ 
       $sql  .= $virgula." r21_valor = $this->r21_valor ";
       $virgula = ",";
       if(trim($this->r21_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "r21_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r21_quant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r21_quant"])){ 
       $sql  .= $virgula." r21_quant = $this->r21_quant ";
       $virgula = ",";
       if(trim($this->r21_quant) == null ){ 
         $this->erro_sql = " Campo Quantidade nao Informado.";
         $this->erro_campo = "r21_quant";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r21_lotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r21_lotac"])){ 
       $sql  .= $virgula." r21_lotac = '$this->r21_lotac' ";
       $virgula = ",";
       if(trim($this->r21_lotac) == null ){ 
         $this->erro_sql = " Campo Lotação nao Informado.";
         $this->erro_campo = "r21_lotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r21_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r21_instit"])){ 
       $sql  .= $virgula." r21_instit = $this->r21_instit ";
       $virgula = ",";
       if(trim($this->r21_instit) == null ){ 
         $this->erro_sql = " Campo codigo da instituicao nao Informado.";
         $this->erro_campo = "r21_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r21_anousu!=null){
       $sql .= " r21_anousu = $this->r21_anousu";
     }
     if($r21_mesusu!=null){
       $sql .= " and  r21_mesusu = $this->r21_mesusu";
     }
     if($r21_regist!=null){
       $sql .= " and  r21_regist = $this->r21_regist";
     }
     if($r21_rubric!=null){
       $sql .= " and  r21_rubric = '$this->r21_rubric'";
     }
     if(trim($where) != ""){
	     if(strpos("where",$sql) != ""){
	     	 $sql .= " and ";
	     }
	     $sql .= $where;
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r21_anousu,$this->r21_mesusu,$this->r21_regist,$this->r21_rubric));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4290,'$this->r21_anousu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4291,'$this->r21_mesusu','A')");
         $resac = db_query("insert into db_acountkey values($acount,4292,'$this->r21_regist','A')");
         $resac = db_query("insert into db_acountkey values($acount,4293,'$this->r21_rubric','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r21_anousu"]) || $this->r21_anousu != "")
           $resac = db_query("insert into db_acount values($acount,576,4290,'".AddSlashes(pg_result($resaco,$conresaco,'r21_anousu'))."','$this->r21_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r21_mesusu"]) || $this->r21_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,576,4291,'".AddSlashes(pg_result($resaco,$conresaco,'r21_mesusu'))."','$this->r21_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r21_regist"]) || $this->r21_regist != "")
           $resac = db_query("insert into db_acount values($acount,576,4292,'".AddSlashes(pg_result($resaco,$conresaco,'r21_regist'))."','$this->r21_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r21_rubric"]) || $this->r21_rubric != "")
           $resac = db_query("insert into db_acount values($acount,576,4293,'".AddSlashes(pg_result($resaco,$conresaco,'r21_rubric'))."','$this->r21_rubric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r21_valor"]) || $this->r21_valor != "")
           $resac = db_query("insert into db_acount values($acount,576,4294,'".AddSlashes(pg_result($resaco,$conresaco,'r21_valor'))."','$this->r21_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r21_quant"]) || $this->r21_quant != "")
           $resac = db_query("insert into db_acount values($acount,576,4295,'".AddSlashes(pg_result($resaco,$conresaco,'r21_quant'))."','$this->r21_quant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r21_lotac"]) || $this->r21_lotac != "")
           $resac = db_query("insert into db_acount values($acount,576,4296,'".AddSlashes(pg_result($resaco,$conresaco,'r21_lotac'))."','$this->r21_lotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r21_instit"]) || $this->r21_instit != "")
           $resac = db_query("insert into db_acount values($acount,576,7463,'".AddSlashes(pg_result($resaco,$conresaco,'r21_instit'))."','$this->r21_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ponto de Adiantamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r21_anousu."-".$this->r21_mesusu."-".$this->r21_regist."-".$this->r21_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ponto de Adiantamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r21_anousu."-".$this->r21_mesusu."-".$this->r21_regist."-".$this->r21_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r21_anousu."-".$this->r21_mesusu."-".$this->r21_regist."-".$this->r21_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r21_anousu=null,$r21_mesusu=null,$r21_regist=null,$r21_rubric=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r21_anousu,$r21_mesusu,$r21_regist,$r21_rubric));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,4290,'$r21_anousu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4291,'$r21_mesusu','E')");
         $resac = db_query("insert into db_acountkey values($acount,4292,'$r21_regist','E')");
         $resac = db_query("insert into db_acountkey values($acount,4293,'$r21_rubric','E')");
         $resac = db_query("insert into db_acount values($acount,576,4290,'','".AddSlashes(pg_result($resaco,$iresaco,'r21_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,576,4291,'','".AddSlashes(pg_result($resaco,$iresaco,'r21_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,576,4292,'','".AddSlashes(pg_result($resaco,$iresaco,'r21_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,576,4293,'','".AddSlashes(pg_result($resaco,$iresaco,'r21_rubric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,576,4294,'','".AddSlashes(pg_result($resaco,$iresaco,'r21_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,576,4295,'','".AddSlashes(pg_result($resaco,$iresaco,'r21_quant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,576,4296,'','".AddSlashes(pg_result($resaco,$iresaco,'r21_lotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,576,7463,'','".AddSlashes(pg_result($resaco,$iresaco,'r21_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pontofa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r21_anousu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r21_anousu = $r21_anousu ";
        }
        if($r21_mesusu != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r21_mesusu = $r21_mesusu ";
        }
        if($r21_regist != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r21_regist = $r21_regist ";
        }
        if($r21_rubric != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r21_rubric = '$r21_rubric' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ponto de Adiantamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r21_anousu."-".$r21_mesusu."-".$r21_regist."-".$r21_rubric;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ponto de Adiantamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r21_anousu."-".$r21_mesusu."-".$r21_regist."-".$r21_rubric;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r21_anousu."-".$r21_mesusu."-".$r21_regist."-".$r21_rubric;
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
        $this->erro_sql   = "Record Vazio na Tabela:pontofa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $r21_anousu=null,$r21_mesusu=null,$r21_regist=null,$r21_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontofa ";
     $sql .= "      inner join db_config  on  db_config.codigo = pontofa.r21_instit";
     $sql .= "      inner join lotacao  on lotacao.r13_anousu = pontofa.r21_anousu 
		                                   and lotacao.r13_mesusu = pontofa.r21_mesusu 
																			 and lotacao.r13_codigo = pontofa.r21_lotac
																			 and lotacao.r13_instit = pontofa.r21_instit";
     $sql .= "      inner join pessoal  on pessoal.r01_anousu = pontofa.r21_anousu 
		                                   and pessoal.r01_mesusu = pontofa.r21_mesusu 
																			 and pessoal.r01_regist = pontofa.r21_regist
																			 and pessoal.r01_instit = pontofa.r21_instit ";
     $sql .= "      inner join rubricas  on rubricas.r06_anousu = pontofa.r21_anousu 
		                                    and rubricas.r06_mesusu = pontofa.r21_mesusu 
																				and rubricas.r06_codigo = pontofa.r21_rubric
																				and rubricas.r06_instit = pontofa.r21_instit";
     $sql .= "      inner join cgm  as d on d.z01_numcgm = pessoal.r01_numcgm";
     $sql .= "      inner join funcao  as d on d.r37_anousu = pessoal.r01_anousu 
		                                       and d.r37_mesusu = pessoal.r01_mesusu 
																					 and d.r37_funcao = pessoal.r01_funcao
																					 and d.r37_instit = pessoal.r01_instit ";
     $sql .= "      inner join inssirf  as d on d.r33_anousu = pessoal.r01_anousu 
		                                        and d.r33_mesusu = pessoal.r01_mesusu 
																						and d.r33_codtab = pessoal.r01_tbprev
																						and d.r33_instit = pessoal.r01_instit";
     $sql .= "      inner join cargo  as d on d.r65_anousu = pessoal.r01_anousu 
		                                      and d.r65_mesusu = pessoal.r01_mesusu 
																					and d.r65_cargo = pessoal.r01_cargo";
     $sql2 = "";
     if($dbwhere==""){
       if($r21_anousu!=null ){
         $sql2 .= " where pontofa.r21_anousu = $r21_anousu "; 
       } 
       if($r21_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofa.r21_mesusu = $r21_mesusu "; 
       } 
       if($r21_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofa.r21_regist = $r21_regist "; 
       } 
       if($r21_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofa.r21_rubric = '$r21_rubric' "; 
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
   function sql_query_file ( $r21_anousu=null,$r21_mesusu=null,$r21_regist=null,$r21_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontofa ";
     $sql2 = "";
     if($dbwhere==""){
       if($r21_anousu!=null ){
         $sql2 .= " where pontofa.r21_anousu = $r21_anousu "; 
       } 
       if($r21_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofa.r21_mesusu = $r21_mesusu "; 
       } 
       if($r21_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofa.r21_regist = $r21_regist "; 
       } 
       if($r21_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofa.r21_rubric = '$r21_rubric' "; 
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
   function sql_query_seleciona ( $r21_anousu=null,$r21_mesusu=null,$r21_regist=null,$r21_rubric=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pontofa ";
     $sql .= "      inner join rhpessoal    on  rhpessoal.rh01_regist = pontofa.r21_regist";     
     $sql .= "      inner join rhpessoalmov on  rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist ";
     $sql .= "                             and  pontofa.r21_anousu          = rhpessoalmov.rh02_anousu ";
     $sql .= "                             and  pontofa.r21_mesusu          = rhpessoalmov.rh02_mesusu ";
     $sql .= "                             and  pontofa.r21_instit          = rhpessoalmov.rh02_instit ";
     $sql .= "      inner join rhfuncao     on  rhpessoalmov.rh02_funcao    = rhfuncao.rh37_funcao     ";
     $sql .= "                             and  rhpessoalmov.rh02_instit    = rhfuncao.rh37_instit     ";
     $sql .= "      inner join rhregime     on  rhpessoalmov.rh02_codreg    = rhregime.rh30_codreg     ";
     $sql .= "                             and  rhpessoalmov.rh02_instit    = rhregime.rh30_instit     ";
     $sql .= "      inner join rhrubricas   on  rhrubricas.rh27_rubric = pontofa.r21_rubric
		                                       and  rhrubricas.rh27_instit = pontofa.r21_instit ";
     $sql .= "      inner join rhlota       on  rhlota.r70_codigo::char(12) = pontofa.r21_lotac
		                                       and  rhlota.r70_instit = pontofa.r21_instit ";
     $sql .= "      inner join cgm          on  cgm.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($r21_anousu!=null ){
         $sql2 .= " where pontofa.r21_anousu = $r21_anousu "; 
       } 
       if($r21_mesusu!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofa.r21_mesusu = $r21_mesusu "; 
       } 
       if($r21_regist!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofa.r21_regist = $r21_regist "; 
       } 
       if($r21_rubric!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " pontofa.r21_rubric = '$r21_rubric' "; 
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