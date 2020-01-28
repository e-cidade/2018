<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
//CLASSE DA ENTIDADE rhemissaochequeitem
class cl_rhemissaochequeitem { 
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
   var $r18_sequencial = 0; 
   var $r18_emissaocheque = 0; 
   var $r18_regist = 0; 
   var $r18_anousu = 0; 
   var $r18_mesusu = 0; 
   var $r18_numcgm = 0; 
   var $r18_numcheque = null; 
   var $r18_valor = 0; 
   var $r18_tipo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 r18_sequencial = int4 = Sequencial 
                 r18_emissaocheque = int4 = Emissão Cheque 
                 r18_regist = int4 = Matrícula 
                 r18_anousu = int4 = Exercício 
                 r18_mesusu = int4 = Mês 
                 r18_numcgm = int4 = Cgm 
                 r18_numcheque = varchar(20) = Número do Cheque 
                 r18_valor = float4 = Valor 
                 r18_tipo = int4 = Tipo 
                 ";
   //funcao construtor da classe 
   function cl_rhemissaochequeitem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhemissaochequeitem"); 
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
       $this->r18_sequencial = ($this->r18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["r18_sequencial"]:$this->r18_sequencial);
       $this->r18_emissaocheque = ($this->r18_emissaocheque == ""?@$GLOBALS["HTTP_POST_VARS"]["r18_emissaocheque"]:$this->r18_emissaocheque);
       $this->r18_regist = ($this->r18_regist == ""?@$GLOBALS["HTTP_POST_VARS"]["r18_regist"]:$this->r18_regist);
       $this->r18_anousu = ($this->r18_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["r18_anousu"]:$this->r18_anousu);
       $this->r18_mesusu = ($this->r18_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["r18_mesusu"]:$this->r18_mesusu);
       $this->r18_numcgm = ($this->r18_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["r18_numcgm"]:$this->r18_numcgm);
       $this->r18_numcheque = ($this->r18_numcheque == ""?@$GLOBALS["HTTP_POST_VARS"]["r18_numcheque"]:$this->r18_numcheque);
       $this->r18_valor = ($this->r18_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["r18_valor"]:$this->r18_valor);
       $this->r18_tipo = ($this->r18_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["r18_tipo"]:$this->r18_tipo);
     }else{
       $this->r18_sequencial = ($this->r18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["r18_sequencial"]:$this->r18_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($r18_sequencial){ 
      $this->atualizacampos();
     if($this->r18_emissaocheque == null ){ 
       $this->erro_sql = " Campo Emissão Cheque nao Informado.";
       $this->erro_campo = "r18_emissaocheque";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r18_regist == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "r18_regist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r18_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "r18_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r18_mesusu == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "r18_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r18_numcgm == null ){ 
       $this->erro_sql = " Campo Cgm nao Informado.";
       $this->erro_campo = "r18_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r18_numcheque == null ){ 
       $this->erro_sql = " Campo Número do Cheque nao Informado.";
       $this->erro_campo = "r18_numcheque";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r18_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "r18_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->r18_tipo == null ){ 
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "r18_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($r18_sequencial == "" || $r18_sequencial == null ){
       $result = db_query("select nextval('rhemissaochequeitem_r18_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhemissaochequeitem_r18_sequencial_seq do campo: r18_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->r18_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhemissaochequeitem_r18_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $r18_sequencial)){
         $this->erro_sql = " Campo r18_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->r18_sequencial = $r18_sequencial; 
       }
     }
     if(($this->r18_sequencial == null) || ($this->r18_sequencial == "") ){ 
       $this->erro_sql = " Campo r18_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhemissaochequeitem(
                                       r18_sequencial 
                                      ,r18_emissaocheque 
                                      ,r18_regist 
                                      ,r18_anousu 
                                      ,r18_mesusu 
                                      ,r18_numcgm 
                                      ,r18_numcheque 
                                      ,r18_valor 
                                      ,r18_tipo 
                       )
                values (
                                $this->r18_sequencial 
                               ,$this->r18_emissaocheque 
                               ,$this->r18_regist 
                               ,$this->r18_anousu 
                               ,$this->r18_mesusu 
                               ,$this->r18_numcgm 
                               ,'$this->r18_numcheque' 
                               ,$this->r18_valor 
                               ,$this->r18_tipo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "rhemissaochequeitem ($this->r18_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "rhemissaochequeitem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "rhemissaochequeitem ($this->r18_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r18_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->r18_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14064,'$this->r18_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2473,14064,'','".AddSlashes(pg_result($resaco,0,'r18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2473,14065,'','".AddSlashes(pg_result($resaco,0,'r18_emissaocheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2473,14067,'','".AddSlashes(pg_result($resaco,0,'r18_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2473,14066,'','".AddSlashes(pg_result($resaco,0,'r18_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2473,14068,'','".AddSlashes(pg_result($resaco,0,'r18_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2473,14094,'','".AddSlashes(pg_result($resaco,0,'r18_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2473,14069,'','".AddSlashes(pg_result($resaco,0,'r18_numcheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2473,14070,'','".AddSlashes(pg_result($resaco,0,'r18_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2473,14071,'','".AddSlashes(pg_result($resaco,0,'r18_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($r18_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhemissaochequeitem set ";
     $virgula = "";
     if(trim($this->r18_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r18_sequencial"])){ 
       $sql  .= $virgula." r18_sequencial = $this->r18_sequencial ";
       $virgula = ",";
       if(trim($this->r18_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "r18_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r18_emissaocheque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r18_emissaocheque"])){ 
       $sql  .= $virgula." r18_emissaocheque = $this->r18_emissaocheque ";
       $virgula = ",";
       if(trim($this->r18_emissaocheque) == null ){ 
         $this->erro_sql = " Campo Emissão Cheque nao Informado.";
         $this->erro_campo = "r18_emissaocheque";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r18_regist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r18_regist"])){ 
       $sql  .= $virgula." r18_regist = $this->r18_regist ";
       $virgula = ",";
       if(trim($this->r18_regist) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "r18_regist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r18_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r18_anousu"])){ 
       $sql  .= $virgula." r18_anousu = $this->r18_anousu ";
       $virgula = ",";
       if(trim($this->r18_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "r18_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r18_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r18_mesusu"])){ 
       $sql  .= $virgula." r18_mesusu = $this->r18_mesusu ";
       $virgula = ",";
       if(trim($this->r18_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "r18_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r18_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r18_numcgm"])){ 
       $sql  .= $virgula." r18_numcgm = $this->r18_numcgm ";
       $virgula = ",";
       if(trim($this->r18_numcgm) == null ){ 
         $this->erro_sql = " Campo Cgm nao Informado.";
         $this->erro_campo = "r18_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r18_numcheque)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r18_numcheque"])){ 
       $sql  .= $virgula." r18_numcheque = '$this->r18_numcheque' ";
       $virgula = ",";
       if(trim($this->r18_numcheque) == null ){ 
         $this->erro_sql = " Campo Número do Cheque nao Informado.";
         $this->erro_campo = "r18_numcheque";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r18_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r18_valor"])){ 
       $sql  .= $virgula." r18_valor = $this->r18_valor ";
       $virgula = ",";
       if(trim($this->r18_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "r18_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->r18_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["r18_tipo"])){ 
       $sql  .= $virgula." r18_tipo = $this->r18_tipo ";
       $virgula = ",";
       if(trim($this->r18_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "r18_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($r18_sequencial!=null){
       $sql .= " r18_sequencial = $this->r18_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->r18_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14064,'$this->r18_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r18_sequencial"]) || $this->r18_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2473,14064,'".AddSlashes(pg_result($resaco,$conresaco,'r18_sequencial'))."','$this->r18_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r18_emissaocheque"]) || $this->r18_emissaocheque != "")
           $resac = db_query("insert into db_acount values($acount,2473,14065,'".AddSlashes(pg_result($resaco,$conresaco,'r18_emissaocheque'))."','$this->r18_emissaocheque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r18_regist"]) || $this->r18_regist != "")
           $resac = db_query("insert into db_acount values($acount,2473,14067,'".AddSlashes(pg_result($resaco,$conresaco,'r18_regist'))."','$this->r18_regist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r18_anousu"]) || $this->r18_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2473,14066,'".AddSlashes(pg_result($resaco,$conresaco,'r18_anousu'))."','$this->r18_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r18_mesusu"]) || $this->r18_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,2473,14068,'".AddSlashes(pg_result($resaco,$conresaco,'r18_mesusu'))."','$this->r18_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r18_numcgm"]) || $this->r18_numcgm != "")
           $resac = db_query("insert into db_acount values($acount,2473,14094,'".AddSlashes(pg_result($resaco,$conresaco,'r18_numcgm'))."','$this->r18_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r18_numcheque"]) || $this->r18_numcheque != "")
           $resac = db_query("insert into db_acount values($acount,2473,14069,'".AddSlashes(pg_result($resaco,$conresaco,'r18_numcheque'))."','$this->r18_numcheque',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r18_valor"]) || $this->r18_valor != "")
           $resac = db_query("insert into db_acount values($acount,2473,14070,'".AddSlashes(pg_result($resaco,$conresaco,'r18_valor'))."','$this->r18_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["r18_tipo"]) || $this->r18_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2473,14071,'".AddSlashes(pg_result($resaco,$conresaco,'r18_tipo'))."','$this->r18_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhemissaochequeitem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->r18_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhemissaochequeitem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->r18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->r18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($r18_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($r18_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14064,'$r18_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2473,14064,'','".AddSlashes(pg_result($resaco,$iresaco,'r18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2473,14065,'','".AddSlashes(pg_result($resaco,$iresaco,'r18_emissaocheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2473,14067,'','".AddSlashes(pg_result($resaco,$iresaco,'r18_regist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2473,14066,'','".AddSlashes(pg_result($resaco,$iresaco,'r18_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2473,14068,'','".AddSlashes(pg_result($resaco,$iresaco,'r18_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2473,14094,'','".AddSlashes(pg_result($resaco,$iresaco,'r18_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2473,14069,'','".AddSlashes(pg_result($resaco,$iresaco,'r18_numcheque'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2473,14070,'','".AddSlashes(pg_result($resaco,$iresaco,'r18_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2473,14071,'','".AddSlashes(pg_result($resaco,$iresaco,'r18_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhemissaochequeitem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($r18_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " r18_sequencial = $r18_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "rhemissaochequeitem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$r18_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "rhemissaochequeitem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$r18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$r18_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhemissaochequeitem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $r18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhemissaochequeitem ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = rhemissaochequeitem.r18_numcgm";
     $sql .= "      inner join rhemissaocheque  on  rhemissaocheque.r15_sequencial = rhemissaochequeitem.r18_emissaocheque";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = rhemissaocheque.r15_idusuario";
     $sql2 = "";
     if($dbwhere==""){
       if($r18_sequencial!=null ){
         $sql2 .= " where rhemissaochequeitem.r18_sequencial = $r18_sequencial "; 
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
   function sql_query_file ( $r18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhemissaochequeitem ";
     $sql2 = "";
     if($dbwhere==""){
       if($r18_sequencial!=null ){
         $sql2 .= " where rhemissaochequeitem.r18_sequencial = $r18_sequencial "; 
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
  
   function sql_query_lota( $r18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhemissaochequeitem ";
     $sql .= "      inner join cgm             on cgm.z01_numcgm                 = rhemissaochequeitem.r18_numcgm        ";
     $sql .= "      inner join rhpessoalmov    on rhpessoalmov.rh02_regist       = rhemissaochequeitem.r18_regist        ";
     $sql .= "                                and rhpessoalmov.rh02_anousu       = rhemissaochequeitem.r18_anousu        ";
     $sql .= "                                and rhpessoalmov.rh02_mesusu       = rhemissaochequeitem.r18_mesusu        ";
     $sql .= "      inner join rhlota          on rhlota.r70_codigo              = rhpessoalmov.rh02_lota                ";
     $sql .= "      inner join rhemissaocheque on rhemissaocheque.r15_sequencial = rhemissaochequeitem.r18_emissaocheque ";
     $sql .= "      inner join db_usuarios     on db_usuarios.id_usuario         = rhemissaocheque.r15_idusuario         ";
     $sql2 = "";
     if($dbwhere==""){
       if($r18_sequencial!=null ){
         $sql2 .= " where rhemissaochequeitem.r18_sequencial = $r18_sequencial "; 
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