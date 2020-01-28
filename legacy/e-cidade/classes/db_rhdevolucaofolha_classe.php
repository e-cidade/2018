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
//CLASSE DA ENTIDADE rhdevolucaofolha
class cl_rhdevolucaofolha { 
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
   var $rh69_sequencial = 0; 
   var $rh69_concarpeculiar = null; 
   var $rh69_recurso = 0; 
   var $rh69_anousu = 0; 
   var $rh69_mesusu = 0; 
   var $rh69_siglaarq = null; 
   var $rh69_tipoempenho = 0; 
   var $rh69_tabprev = 0; 
   var $rh69_seqcompl = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh69_sequencial = int4 = Sequencial 
                 rh69_concarpeculiar = varchar(100) = Característica Peculiar 
                 rh69_recurso = int4 = Recurso 
                 rh69_anousu = int4 = Ano 
                 rh69_mesusu = int4 = Mês 
                 rh69_siglaarq = char(3) = Sigla 
                 rh69_tipoempenho = int4 = Tipo Empenho 
                 rh69_tabprev = int4 = Previdência 
                 rh69_seqcompl = int4 = Complementar 
                 ";
   //funcao construtor da classe 
   function cl_rhdevolucaofolha() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhdevolucaofolha"); 
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
       $this->rh69_sequencial = ($this->rh69_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh69_sequencial"]:$this->rh69_sequencial);
       $this->rh69_concarpeculiar = ($this->rh69_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["rh69_concarpeculiar"]:$this->rh69_concarpeculiar);
       $this->rh69_recurso = ($this->rh69_recurso == ""?@$GLOBALS["HTTP_POST_VARS"]["rh69_recurso"]:$this->rh69_recurso);
       $this->rh69_anousu = ($this->rh69_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh69_anousu"]:$this->rh69_anousu);
       $this->rh69_mesusu = ($this->rh69_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh69_mesusu"]:$this->rh69_mesusu);
       $this->rh69_siglaarq = ($this->rh69_siglaarq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh69_siglaarq"]:$this->rh69_siglaarq);
       $this->rh69_tipoempenho = ($this->rh69_tipoempenho == ""?@$GLOBALS["HTTP_POST_VARS"]["rh69_tipoempenho"]:$this->rh69_tipoempenho);
       $this->rh69_tabprev = ($this->rh69_tabprev == ""?@$GLOBALS["HTTP_POST_VARS"]["rh69_tabprev"]:$this->rh69_tabprev);
       $this->rh69_seqcompl = ($this->rh69_seqcompl == ""?@$GLOBALS["HTTP_POST_VARS"]["rh69_seqcompl"]:$this->rh69_seqcompl);
     }else{
       $this->rh69_sequencial = ($this->rh69_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh69_sequencial"]:$this->rh69_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh69_sequencial){ 
      $this->atualizacampos();
     if($this->rh69_concarpeculiar == null ){ 
       $this->erro_sql = " Campo Característica Peculiar nao Informado.";
       $this->erro_campo = "rh69_concarpeculiar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh69_recurso == null ){ 
       $this->erro_sql = " Campo Recurso nao Informado.";
       $this->erro_campo = "rh69_recurso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh69_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "rh69_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh69_mesusu == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "rh69_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh69_siglaarq == null ){ 
       $this->erro_sql = " Campo Sigla nao Informado.";
       $this->erro_campo = "rh69_siglaarq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh69_tipoempenho == null ){ 
       $this->erro_sql = " Campo Tipo Empenho nao Informado.";
       $this->erro_campo = "rh69_tipoempenho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh69_tabprev == null ){ 
       $this->erro_sql = " Campo Previdência nao Informado.";
       $this->erro_campo = "rh69_tabprev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh69_seqcompl == null ){ 
       $this->erro_sql = " Campo Complementar nao Informado.";
       $this->erro_campo = "rh69_seqcompl";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh69_sequencial == "" || $rh69_sequencial == null ){
       $result = db_query("select nextval('rhdevolucaofolha_rh69_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhdevolucaofolha_rh69_sequencial_seq do campo: rh69_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh69_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhdevolucaofolha_rh69_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh69_sequencial)){
         $this->erro_sql = " Campo rh69_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh69_sequencial = $rh69_sequencial; 
       }
     }
     if(($this->rh69_sequencial == null) || ($this->rh69_sequencial == "") ){ 
       $this->erro_sql = " Campo rh69_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhdevolucaofolha(
                                       rh69_sequencial 
                                      ,rh69_concarpeculiar 
                                      ,rh69_recurso 
                                      ,rh69_anousu 
                                      ,rh69_mesusu 
                                      ,rh69_siglaarq 
                                      ,rh69_tipoempenho 
                                      ,rh69_tabprev 
                                      ,rh69_seqcompl 
                       )
                values (
                                $this->rh69_sequencial 
                               ,'$this->rh69_concarpeculiar' 
                               ,$this->rh69_recurso 
                               ,$this->rh69_anousu 
                               ,$this->rh69_mesusu 
                               ,'$this->rh69_siglaarq' 
                               ,$this->rh69_tipoempenho 
                               ,$this->rh69_tabprev 
                               ,$this->rh69_seqcompl 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Devoluções da Folha ($this->rh69_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Devoluções da Folha já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Devoluções da Folha ($this->rh69_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh69_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh69_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15057,'$this->rh69_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2648,15057,'','".AddSlashes(pg_result($resaco,0,'rh69_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2648,15058,'','".AddSlashes(pg_result($resaco,0,'rh69_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2648,15059,'','".AddSlashes(pg_result($resaco,0,'rh69_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2648,15060,'','".AddSlashes(pg_result($resaco,0,'rh69_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2648,15061,'','".AddSlashes(pg_result($resaco,0,'rh69_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2648,15062,'','".AddSlashes(pg_result($resaco,0,'rh69_siglaarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2648,15063,'','".AddSlashes(pg_result($resaco,0,'rh69_tipoempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2648,15064,'','".AddSlashes(pg_result($resaco,0,'rh69_tabprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2648,15065,'','".AddSlashes(pg_result($resaco,0,'rh69_seqcompl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh69_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhdevolucaofolha set ";
     $virgula = "";
     if(trim($this->rh69_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh69_sequencial"])){ 
       $sql  .= $virgula." rh69_sequencial = $this->rh69_sequencial ";
       $virgula = ",";
       if(trim($this->rh69_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh69_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh69_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh69_concarpeculiar"])){ 
       $sql  .= $virgula." rh69_concarpeculiar = '$this->rh69_concarpeculiar' ";
       $virgula = ",";
       if(trim($this->rh69_concarpeculiar) == null ){ 
         $this->erro_sql = " Campo Característica Peculiar nao Informado.";
         $this->erro_campo = "rh69_concarpeculiar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh69_recurso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh69_recurso"])){ 
       $sql  .= $virgula." rh69_recurso = $this->rh69_recurso ";
       $virgula = ",";
       if(trim($this->rh69_recurso) == null ){ 
         $this->erro_sql = " Campo Recurso nao Informado.";
         $this->erro_campo = "rh69_recurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh69_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh69_anousu"])){ 
       $sql  .= $virgula." rh69_anousu = $this->rh69_anousu ";
       $virgula = ",";
       if(trim($this->rh69_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "rh69_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh69_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh69_mesusu"])){ 
       $sql  .= $virgula." rh69_mesusu = $this->rh69_mesusu ";
       $virgula = ",";
       if(trim($this->rh69_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "rh69_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh69_siglaarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh69_siglaarq"])){ 
       $sql  .= $virgula." rh69_siglaarq = '$this->rh69_siglaarq' ";
       $virgula = ",";
       if(trim($this->rh69_siglaarq) == null ){ 
         $this->erro_sql = " Campo Sigla nao Informado.";
         $this->erro_campo = "rh69_siglaarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh69_tipoempenho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh69_tipoempenho"])){ 
       $sql  .= $virgula." rh69_tipoempenho = $this->rh69_tipoempenho ";
       $virgula = ",";
       if(trim($this->rh69_tipoempenho) == null ){ 
         $this->erro_sql = " Campo Tipo Empenho nao Informado.";
         $this->erro_campo = "rh69_tipoempenho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh69_tabprev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh69_tabprev"])){ 
       $sql  .= $virgula." rh69_tabprev = $this->rh69_tabprev ";
       $virgula = ",";
       if(trim($this->rh69_tabprev) == null ){ 
         $this->erro_sql = " Campo Previdência nao Informado.";
         $this->erro_campo = "rh69_tabprev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh69_seqcompl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh69_seqcompl"])){ 
       $sql  .= $virgula." rh69_seqcompl = $this->rh69_seqcompl ";
       $virgula = ",";
       if(trim($this->rh69_seqcompl) == null ){ 
         $this->erro_sql = " Campo Complementar nao Informado.";
         $this->erro_campo = "rh69_seqcompl";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh69_sequencial!=null){
       $sql .= " rh69_sequencial = $this->rh69_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh69_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15057,'$this->rh69_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh69_sequencial"]) || $this->rh69_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2648,15057,'".AddSlashes(pg_result($resaco,$conresaco,'rh69_sequencial'))."','$this->rh69_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh69_concarpeculiar"]) || $this->rh69_concarpeculiar != "")
           $resac = db_query("insert into db_acount values($acount,2648,15058,'".AddSlashes(pg_result($resaco,$conresaco,'rh69_concarpeculiar'))."','$this->rh69_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh69_recurso"]) || $this->rh69_recurso != "")
           $resac = db_query("insert into db_acount values($acount,2648,15059,'".AddSlashes(pg_result($resaco,$conresaco,'rh69_recurso'))."','$this->rh69_recurso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh69_anousu"]) || $this->rh69_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2648,15060,'".AddSlashes(pg_result($resaco,$conresaco,'rh69_anousu'))."','$this->rh69_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh69_mesusu"]) || $this->rh69_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,2648,15061,'".AddSlashes(pg_result($resaco,$conresaco,'rh69_mesusu'))."','$this->rh69_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh69_siglaarq"]) || $this->rh69_siglaarq != "")
           $resac = db_query("insert into db_acount values($acount,2648,15062,'".AddSlashes(pg_result($resaco,$conresaco,'rh69_siglaarq'))."','$this->rh69_siglaarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh69_tipoempenho"]) || $this->rh69_tipoempenho != "")
           $resac = db_query("insert into db_acount values($acount,2648,15063,'".AddSlashes(pg_result($resaco,$conresaco,'rh69_tipoempenho'))."','$this->rh69_tipoempenho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh69_tabprev"]) || $this->rh69_tabprev != "")
           $resac = db_query("insert into db_acount values($acount,2648,15064,'".AddSlashes(pg_result($resaco,$conresaco,'rh69_tabprev'))."','$this->rh69_tabprev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh69_seqcompl"]) || $this->rh69_seqcompl != "")
           $resac = db_query("insert into db_acount values($acount,2648,15065,'".AddSlashes(pg_result($resaco,$conresaco,'rh69_seqcompl'))."','$this->rh69_seqcompl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Devoluções da Folha nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh69_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Devoluções da Folha nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh69_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh69_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh69_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh69_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15057,'$rh69_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2648,15057,'','".AddSlashes(pg_result($resaco,$iresaco,'rh69_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2648,15058,'','".AddSlashes(pg_result($resaco,$iresaco,'rh69_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2648,15059,'','".AddSlashes(pg_result($resaco,$iresaco,'rh69_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2648,15060,'','".AddSlashes(pg_result($resaco,$iresaco,'rh69_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2648,15061,'','".AddSlashes(pg_result($resaco,$iresaco,'rh69_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2648,15062,'','".AddSlashes(pg_result($resaco,$iresaco,'rh69_siglaarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2648,15063,'','".AddSlashes(pg_result($resaco,$iresaco,'rh69_tipoempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2648,15064,'','".AddSlashes(pg_result($resaco,$iresaco,'rh69_tabprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2648,15065,'','".AddSlashes(pg_result($resaco,$iresaco,'rh69_seqcompl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhdevolucaofolha
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh69_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh69_sequencial = $rh69_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Devoluções da Folha nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh69_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Devoluções da Folha nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh69_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh69_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhdevolucaofolha";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh69_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhdevolucaofolha ";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = rhdevolucaofolha.rh69_recurso";
     $sql2 = "";
     if($dbwhere==""){
       if($rh69_sequencial!=null ){
         $sql2 .= " where rhdevolucaofolha.rh69_sequencial = $rh69_sequencial "; 
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
   function sql_query_file ( $rh69_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhdevolucaofolha ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh69_sequencial!=null ){
         $sql2 .= " where rhdevolucaofolha.rh69_sequencial = $rh69_sequencial "; 
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
  
   function sql_query_rubricas( $rh69_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhdevolucaofolha ";
     $sql .= "      left  join rhdevolucaofolharhemprubrica  on rhdevolucaofolharhemprubrica.rh87_devolucaofolha         = rhdevolucaofolha.rh69_sequencial                        ";
     $sql .= "      left  join rhempenhofolharubrica         on rhempenhofolharubrica.rh73_sequencial                    = rhdevolucaofolharhemprubrica.rh87_rhempenhofolharubrica ";
     $sql .= "      left  join rhempenhofolharubricaretencao on rhempenhofolharubricaretencao.rh78_rhempenhofolharubrica = rhempenhofolharubrica.rh73_sequencial                   ";
     $sql .= "      left  join retencaotiporec               on retencaotiporec.e21_sequencial                           = rhempenhofolharubricaretencao.rh78_retencaotiporec      ";
     $sql .= "      left  join retencaotiporeccgm            on e48_retencaotiporec                                      = e21_sequencial                                          ";
     $sql .= "      left  join rhcontasrec                   on rh41_codigo                                              = rh69_recurso                                            ";
     $sql .= "                                              and rh41_anousu                                              = rh69_anousu                                             ";
     $sql .= "                                              and rh41_instit                                              = rh73_instit                                             "; 
     $sql .= "      inner join orctiporec                    on orctiporec.o15_codigo                                    = rhdevolucaofolha.rh69_recurso                           ";
     $sql .= "      inner join concarpeculiar                on concarpeculiar.c58_sequencial                            = rhdevolucaofolha.rh69_concarpeculiar                    ";
     
     $sql2 = "";
     if($dbwhere==""){
       if($rh69_sequencial!=null ){
         $sql2 .= " where rhdevolucaofolha.rh69_sequencial = $rh69_sequencial "; 
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