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

//MODULO: ISSQN
//CLASSE DA ENTIDADE meiimporta
class cl_meiimporta { 
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
   var $q104_sequencial = 0; 
   var $q104_id_usuario = 0; 
   var $q104_anousu = 0; 
   var $q104_mesusu = 0; 
   var $q104_nomearq = null; 
   var $q104_arquivo = null; 
   var $q104_xml = null; 
   var $q104_tipoimporta = 0; 
   var $q104_cancelado = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q104_sequencial = int4 = Sequencial 
                 q104_id_usuario = int4 = Usuário 
                 q104_anousu = int4 = Ano 
                 q104_mesusu = int4 = Mês 
                 q104_nomearq = varchar(50) = Nome Arquivo 
                 q104_arquivo = text = Arquivo 
                 q104_xml = text = XML do Arquivo 
                 q104_tipoimporta = int4 = Tipo Importação 
                 q104_cancelado = bool = Cancelado 
                 ";
   //funcao construtor da classe 
   function cl_meiimporta() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("meiimporta"); 
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
       $this->q104_sequencial = ($this->q104_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q104_sequencial"]:$this->q104_sequencial);
       $this->q104_id_usuario = ($this->q104_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["q104_id_usuario"]:$this->q104_id_usuario);
       $this->q104_anousu = ($this->q104_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["q104_anousu"]:$this->q104_anousu);
       $this->q104_mesusu = ($this->q104_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["q104_mesusu"]:$this->q104_mesusu);
       $this->q104_nomearq = ($this->q104_nomearq == ""?@$GLOBALS["HTTP_POST_VARS"]["q104_nomearq"]:$this->q104_nomearq);
       $this->q104_arquivo = ($this->q104_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["q104_arquivo"]:$this->q104_arquivo);
       $this->q104_xml = ($this->q104_xml == ""?@$GLOBALS["HTTP_POST_VARS"]["q104_xml"]:$this->q104_xml);
       $this->q104_tipoimporta = ($this->q104_tipoimporta == ""?@$GLOBALS["HTTP_POST_VARS"]["q104_tipoimporta"]:$this->q104_tipoimporta);
       $this->q104_cancelado = ($this->q104_cancelado == "f"?@$GLOBALS["HTTP_POST_VARS"]["q104_cancelado"]:$this->q104_cancelado);
     }else{
       $this->q104_sequencial = ($this->q104_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["q104_sequencial"]:$this->q104_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($q104_sequencial){ 
      $this->atualizacampos();
     if($this->q104_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "q104_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q104_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "q104_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q104_mesusu == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "q104_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q104_tipoimporta == null ){ 
       $this->erro_sql = " Campo Tipo Importação nao Informado.";
       $this->erro_campo = "q104_tipoimporta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->q104_cancelado == null ){ 
       $this->erro_sql = " Campo Cancelado nao Informado.";
       $this->erro_campo = "q104_cancelado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($q104_sequencial == "" || $q104_sequencial == null ){
       $result = db_query("select nextval('meiimporta_q104_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: meiimporta_q104_sequencial_seq do campo: q104_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->q104_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from meiimporta_q104_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $q104_sequencial)){
         $this->erro_sql = " Campo q104_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->q104_sequencial = $q104_sequencial; 
       }
     }
     if(($this->q104_sequencial == null) || ($this->q104_sequencial == "") ){ 
       $this->erro_sql = " Campo q104_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into meiimporta(
                                       q104_sequencial 
                                      ,q104_id_usuario 
                                      ,q104_anousu 
                                      ,q104_mesusu 
                                      ,q104_nomearq 
                                      ,q104_arquivo 
                                      ,q104_xml 
                                      ,q104_tipoimporta 
                                      ,q104_cancelado 
                       )
                values (
                                $this->q104_sequencial 
                               ,$this->q104_id_usuario 
                               ,$this->q104_anousu 
                               ,$this->q104_mesusu 
                               ,'$this->q104_nomearq' 
                               ,'$this->q104_arquivo' 
                               ,'$this->q104_xml' 
                               ,$this->q104_tipoimporta 
                               ,'$this->q104_cancelado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "meiimporta ($this->q104_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "meiimporta já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "meiimporta ($this->q104_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q104_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q104_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16212,'$this->q104_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2845,16212,'','".AddSlashes(pg_result($resaco,0,'q104_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2845,16213,'','".AddSlashes(pg_result($resaco,0,'q104_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2845,16214,'','".AddSlashes(pg_result($resaco,0,'q104_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2845,16215,'','".AddSlashes(pg_result($resaco,0,'q104_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2845,16216,'','".AddSlashes(pg_result($resaco,0,'q104_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2845,16218,'','".AddSlashes(pg_result($resaco,0,'q104_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2845,16219,'','".AddSlashes(pg_result($resaco,0,'q104_xml'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2845,16686,'','".AddSlashes(pg_result($resaco,0,'q104_tipoimporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2845,16687,'','".AddSlashes(pg_result($resaco,0,'q104_cancelado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q104_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update meiimporta set ";
     $virgula = "";
     if(trim($this->q104_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q104_sequencial"])){ 
       $sql  .= $virgula." q104_sequencial = $this->q104_sequencial ";
       $virgula = ",";
       if(trim($this->q104_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "q104_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q104_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q104_id_usuario"])){ 
       $sql  .= $virgula." q104_id_usuario = $this->q104_id_usuario ";
       $virgula = ",";
       if(trim($this->q104_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "q104_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q104_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q104_anousu"])){ 
       $sql  .= $virgula." q104_anousu = $this->q104_anousu ";
       $virgula = ",";
       if(trim($this->q104_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "q104_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q104_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q104_mesusu"])){ 
       $sql  .= $virgula." q104_mesusu = $this->q104_mesusu ";
       $virgula = ",";
       if(trim($this->q104_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "q104_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q104_nomearq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q104_nomearq"])){ 
       $sql  .= $virgula." q104_nomearq = '$this->q104_nomearq' ";
       $virgula = ",";
     }
     if(trim($this->q104_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q104_arquivo"])){ 
       $sql  .= $virgula." q104_arquivo = '$this->q104_arquivo' ";
       $virgula = ",";
     }
     if(trim($this->q104_xml)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q104_xml"])){ 
       $sql  .= $virgula." q104_xml = '$this->q104_xml' ";
       $virgula = ",";
     }
     if(trim($this->q104_tipoimporta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q104_tipoimporta"])){ 
       $sql  .= $virgula." q104_tipoimporta = $this->q104_tipoimporta ";
       $virgula = ",";
       if(trim($this->q104_tipoimporta) == null ){ 
         $this->erro_sql = " Campo Tipo Importação nao Informado.";
         $this->erro_campo = "q104_tipoimporta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q104_cancelado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q104_cancelado"])){ 
       $sql  .= $virgula." q104_cancelado = '$this->q104_cancelado' ";
       $virgula = ",";
       if(trim($this->q104_cancelado) == null ){ 
         $this->erro_sql = " Campo Cancelado nao Informado.";
         $this->erro_campo = "q104_cancelado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($q104_sequencial!=null){
       $sql .= " q104_sequencial = $this->q104_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q104_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16212,'$this->q104_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q104_sequencial"]) || $this->q104_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2845,16212,'".AddSlashes(pg_result($resaco,$conresaco,'q104_sequencial'))."','$this->q104_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q104_id_usuario"]) || $this->q104_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2845,16213,'".AddSlashes(pg_result($resaco,$conresaco,'q104_id_usuario'))."','$this->q104_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q104_anousu"]) || $this->q104_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2845,16214,'".AddSlashes(pg_result($resaco,$conresaco,'q104_anousu'))."','$this->q104_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q104_mesusu"]) || $this->q104_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,2845,16215,'".AddSlashes(pg_result($resaco,$conresaco,'q104_mesusu'))."','$this->q104_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q104_nomearq"]) || $this->q104_nomearq != "")
           $resac = db_query("insert into db_acount values($acount,2845,16216,'".AddSlashes(pg_result($resaco,$conresaco,'q104_nomearq'))."','$this->q104_nomearq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q104_arquivo"]) || $this->q104_arquivo != "")
           $resac = db_query("insert into db_acount values($acount,2845,16218,'".AddSlashes(pg_result($resaco,$conresaco,'q104_arquivo'))."','$this->q104_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q104_xml"]) || $this->q104_xml != "")
           $resac = db_query("insert into db_acount values($acount,2845,16219,'".AddSlashes(pg_result($resaco,$conresaco,'q104_xml'))."','$this->q104_xml',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q104_tipoimporta"]) || $this->q104_tipoimporta != "")
           $resac = db_query("insert into db_acount values($acount,2845,16686,'".AddSlashes(pg_result($resaco,$conresaco,'q104_tipoimporta'))."','$this->q104_tipoimporta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q104_cancelado"]) || $this->q104_cancelado != "")
           $resac = db_query("insert into db_acount values($acount,2845,16687,'".AddSlashes(pg_result($resaco,$conresaco,'q104_cancelado'))."','$this->q104_cancelado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "meiimporta nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q104_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "meiimporta nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q104_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q104_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q104_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q104_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16212,'$q104_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2845,16212,'','".AddSlashes(pg_result($resaco,$iresaco,'q104_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2845,16213,'','".AddSlashes(pg_result($resaco,$iresaco,'q104_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2845,16214,'','".AddSlashes(pg_result($resaco,$iresaco,'q104_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2845,16215,'','".AddSlashes(pg_result($resaco,$iresaco,'q104_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2845,16216,'','".AddSlashes(pg_result($resaco,$iresaco,'q104_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2845,16218,'','".AddSlashes(pg_result($resaco,$iresaco,'q104_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2845,16219,'','".AddSlashes(pg_result($resaco,$iresaco,'q104_xml'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2845,16686,'','".AddSlashes(pg_result($resaco,$iresaco,'q104_tipoimporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2845,16687,'','".AddSlashes(pg_result($resaco,$iresaco,'q104_cancelado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from meiimporta
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q104_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q104_sequencial = $q104_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "meiimporta nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q104_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "meiimporta nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q104_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q104_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:meiimporta";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $q104_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiimporta ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = meiimporta.q104_id_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($q104_sequencial!=null ){
         $sql2 .= " where meiimporta.q104_sequencial = $q104_sequencial "; 
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
   function sql_query_file ( $q104_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiimporta ";
     $sql2 = "";
     if($dbwhere==""){
       if($q104_sequencial!=null ){
         $sql2 .= " where meiimporta.q104_sequencial = $q104_sequencial "; 
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
 
  function sql_query_reg( $q104_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     } else {
       $sql .= $campos;
     }
     
     $sql .= " from meiimporta ";
     $sql .= "      inner join db_usuarios      on db_usuarios.id_usuario               = meiimporta.q104_id_usuario       ";
     $sql .= "      inner join meiimportamei    on meiimportamei.q105_meiimporta        = meiimporta.q104_sequencial       ";
     $sql .= "      inner join meiimportameireg on meiimportameireg.q111_meiimportamei  = meiimportamei.q105_sequencial    ";
     $sql .= "      inner join meievento        on meievento.q101_sequencial            = meiimportameireg.q111_meievento  ";
     $sql .= "      left  join meiprocessareg   on meiprocessareg.q112_meiimportameireg = meiimportameireg.q111_sequencial ";
     
     $sql2 = "";
     
     if($dbwhere==""){
       if($q104_sequencial!=null ){
         $sql2 .= " where meiimporta.q104_sequencial = $q104_sequencial "; 
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
  
   function sql_query_dados( $q104_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     } else {
       $sql .= $campos;
     }
     
     $sql .= " from meiimporta ";
     $sql .= "      inner join db_usuarios                 on db_usuarios.id_usuario                      = meiimporta.q104_id_usuario       ";
     $sql .= "      inner join meiimportamei               on meiimportamei.q105_meiimporta               = meiimporta.q104_sequencial       ";
     $sql .= "      inner join meiimportameireg            on meiimportameireg.q111_meiimportamei         = meiimportamei.q105_sequencial    ";
     $sql .= "      inner join meievento                   on meievento.q101_sequencial                   = meiimportameireg.q111_meievento ";
     $sql .= "      left  join meiimportameiregatividade   on meiimportameiregatividade.q106_sequencial   = meiimportameireg.q111_meiimportameiregatividade   ";
     $sql .= "      left  join meiimportameiregempresa     on meiimportameiregempresa.q107_sequencial     = meiimportameireg.q111_meiimportameiregempresa     ";
     $sql .= "      left  join meiimportameiregresponsavel on meiimportameiregresponsavel.q108_sequencial = meiimportameireg.q111_meiimportameiregresponsavel ";
     $sql .= "      left  join meiimportameiregcontador    on meiimportameiregcontador.q109_sequencial    = meiimportameireg.q111_meiimportameiregcontador    ";
     
     $sql2 = "";
     
     if($dbwhere==""){
       if($q104_sequencial!=null ){
         $sql2 .= " where meiimporta.q104_sequencial = $q104_sequencial "; 
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
  
  // retorna cancelamento de competência sem movimento
  function sql_query_semmov ( $q104_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from meiimporta ";
     $sql .= "      inner join db_usuarios      on  db_usuarios.id_usuario          = meiimporta.q104_id_usuario  ";
     $sql .= "      left  join meiimportasemmov on meiimportasemmov.q114_meiimporta = meiimporta.q104_sequencial  ";
     $sql2 = "";
     if($dbwhere==""){
       if($q104_sequencial!=null ){
         $sql2 .= " where meiimporta.q104_sequencial = $q104_sequencial "; 
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