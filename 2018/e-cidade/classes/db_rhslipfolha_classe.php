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
//CLASSE DA ENTIDADE rhslipfolha
class cl_rhslipfolha { 
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
   var $rh79_sequencial = 0; 
   var $rh79_recurso = 0; 
   var $rh79_anousu = 0; 
   var $rh79_mesusu = 0; 
   var $rh79_siglaarq = null; 
   var $rh79_tipoempenho = 0; 
   var $rh79_tabprev = 0; 
   var $rh79_seqcompl = 0; 
   var $rh79_concarpeculiar = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh79_sequencial = int4 = Sequencial 
                 rh79_recurso = int4 = Recurso 
                 rh79_anousu = int4 = Exercício 
                 rh79_mesusu = int4 = Mês 
                 rh79_siglaarq = char(3) = Sigla 
                 rh79_tipoempenho = int4 = Tipo Empenho 
                 rh79_tabprev = int4 = Previdência 
                 rh79_seqcompl = int4 = Sequencial de Folha Complementar 
                 rh79_concarpeculiar = varchar(100) = Caracteristica Peculiar 
                 ";
   //funcao construtor da classe 
   function cl_rhslipfolha() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rhslipfolha"); 
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
       $this->rh79_sequencial = ($this->rh79_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh79_sequencial"]:$this->rh79_sequencial);
       $this->rh79_recurso = ($this->rh79_recurso == ""?@$GLOBALS["HTTP_POST_VARS"]["rh79_recurso"]:$this->rh79_recurso);
       $this->rh79_anousu = ($this->rh79_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh79_anousu"]:$this->rh79_anousu);
       $this->rh79_mesusu = ($this->rh79_mesusu == ""?@$GLOBALS["HTTP_POST_VARS"]["rh79_mesusu"]:$this->rh79_mesusu);
       $this->rh79_siglaarq = ($this->rh79_siglaarq == ""?@$GLOBALS["HTTP_POST_VARS"]["rh79_siglaarq"]:$this->rh79_siglaarq);
       $this->rh79_tipoempenho = ($this->rh79_tipoempenho == ""?@$GLOBALS["HTTP_POST_VARS"]["rh79_tipoempenho"]:$this->rh79_tipoempenho);
       $this->rh79_tabprev = ($this->rh79_tabprev == ""?@$GLOBALS["HTTP_POST_VARS"]["rh79_tabprev"]:$this->rh79_tabprev);
       $this->rh79_seqcompl = ($this->rh79_seqcompl == ""?@$GLOBALS["HTTP_POST_VARS"]["rh79_seqcompl"]:$this->rh79_seqcompl);
       $this->rh79_concarpeculiar = ($this->rh79_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["rh79_concarpeculiar"]:$this->rh79_concarpeculiar);
     }else{
       $this->rh79_sequencial = ($this->rh79_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["rh79_sequencial"]:$this->rh79_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($rh79_sequencial){ 
      $this->atualizacampos();
     if($this->rh79_recurso == null ){ 
       $this->erro_sql = " Campo Recurso nao Informado.";
       $this->erro_campo = "rh79_recurso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh79_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "rh79_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh79_mesusu == null ){ 
       $this->erro_sql = " Campo Mês nao Informado.";
       $this->erro_campo = "rh79_mesusu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh79_siglaarq == null ){ 
       $this->erro_sql = " Campo Sigla nao Informado.";
       $this->erro_campo = "rh79_siglaarq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh79_tipoempenho == null ){ 
       $this->erro_sql = " Campo Tipo Empenho nao Informado.";
       $this->erro_campo = "rh79_tipoempenho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh79_tabprev == null ){ 
       $this->erro_sql = " Campo Previdência nao Informado.";
       $this->erro_campo = "rh79_tabprev";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh79_seqcompl == null ){ 
       $this->erro_sql = " Campo Sequencial de Folha Complementar nao Informado.";
       $this->erro_campo = "rh79_seqcompl";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh79_concarpeculiar == null ){ 
       $this->erro_sql = " Campo Caracteristica Peculiar nao Informado.";
       $this->erro_campo = "rh79_concarpeculiar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($rh79_sequencial == "" || $rh79_sequencial == null ){
       $result = db_query("select nextval('rhslipfolha_rh79_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rhslipfolha_rh79_sequencial_seq do campo: rh79_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh79_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from rhslipfolha_rh79_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh79_sequencial)){
         $this->erro_sql = " Campo rh79_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh79_sequencial = $rh79_sequencial; 
       }
     }
     if(($this->rh79_sequencial == null) || ($this->rh79_sequencial == "") ){ 
       $this->erro_sql = " Campo rh79_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rhslipfolha(
                                       rh79_sequencial 
                                      ,rh79_recurso 
                                      ,rh79_anousu 
                                      ,rh79_mesusu 
                                      ,rh79_siglaarq 
                                      ,rh79_tipoempenho 
                                      ,rh79_tabprev 
                                      ,rh79_seqcompl 
                                      ,rh79_concarpeculiar 
                       )
                values (
                                $this->rh79_sequencial 
                               ,$this->rh79_recurso 
                               ,$this->rh79_anousu 
                               ,$this->rh79_mesusu 
                               ,'$this->rh79_siglaarq' 
                               ,$this->rh79_tipoempenho 
                               ,$this->rh79_tabprev 
                               ,$this->rh79_seqcompl 
                               ,'$this->rh79_concarpeculiar' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Slip Folha ($this->rh79_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Slip Folha já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Slip Folha ($this->rh79_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh79_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh79_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14415,'$this->rh79_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2541,14415,'','".AddSlashes(pg_result($resaco,0,'rh79_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2541,14416,'','".AddSlashes(pg_result($resaco,0,'rh79_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2541,14417,'','".AddSlashes(pg_result($resaco,0,'rh79_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2541,14418,'','".AddSlashes(pg_result($resaco,0,'rh79_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2541,14419,'','".AddSlashes(pg_result($resaco,0,'rh79_siglaarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2541,14420,'','".AddSlashes(pg_result($resaco,0,'rh79_tipoempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2541,14421,'','".AddSlashes(pg_result($resaco,0,'rh79_tabprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2541,14422,'','".AddSlashes(pg_result($resaco,0,'rh79_seqcompl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2541,15049,'','".AddSlashes(pg_result($resaco,0,'rh79_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh79_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update rhslipfolha set ";
     $virgula = "";
     if(trim($this->rh79_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh79_sequencial"])){ 
       $sql  .= $virgula." rh79_sequencial = $this->rh79_sequencial ";
       $virgula = ",";
       if(trim($this->rh79_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "rh79_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh79_recurso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh79_recurso"])){ 
       $sql  .= $virgula." rh79_recurso = $this->rh79_recurso ";
       $virgula = ",";
       if(trim($this->rh79_recurso) == null ){ 
         $this->erro_sql = " Campo Recurso nao Informado.";
         $this->erro_campo = "rh79_recurso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh79_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh79_anousu"])){ 
       $sql  .= $virgula." rh79_anousu = $this->rh79_anousu ";
       $virgula = ",";
       if(trim($this->rh79_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "rh79_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh79_mesusu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh79_mesusu"])){ 
       $sql  .= $virgula." rh79_mesusu = $this->rh79_mesusu ";
       $virgula = ",";
       if(trim($this->rh79_mesusu) == null ){ 
         $this->erro_sql = " Campo Mês nao Informado.";
         $this->erro_campo = "rh79_mesusu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh79_siglaarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh79_siglaarq"])){ 
       $sql  .= $virgula." rh79_siglaarq = '$this->rh79_siglaarq' ";
       $virgula = ",";
       if(trim($this->rh79_siglaarq) == null ){ 
         $this->erro_sql = " Campo Sigla nao Informado.";
         $this->erro_campo = "rh79_siglaarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh79_tipoempenho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh79_tipoempenho"])){ 
       $sql  .= $virgula." rh79_tipoempenho = $this->rh79_tipoempenho ";
       $virgula = ",";
       if(trim($this->rh79_tipoempenho) == null ){ 
         $this->erro_sql = " Campo Tipo Empenho nao Informado.";
         $this->erro_campo = "rh79_tipoempenho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh79_tabprev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh79_tabprev"])){ 
       $sql  .= $virgula." rh79_tabprev = $this->rh79_tabprev ";
       $virgula = ",";
       if(trim($this->rh79_tabprev) == null ){ 
         $this->erro_sql = " Campo Previdência nao Informado.";
         $this->erro_campo = "rh79_tabprev";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh79_seqcompl)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh79_seqcompl"])){ 
       $sql  .= $virgula." rh79_seqcompl = $this->rh79_seqcompl ";
       $virgula = ",";
       if(trim($this->rh79_seqcompl) == null ){ 
         $this->erro_sql = " Campo Sequencial de Folha Complementar nao Informado.";
         $this->erro_campo = "rh79_seqcompl";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh79_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh79_concarpeculiar"])){ 
       $sql  .= $virgula." rh79_concarpeculiar = '$this->rh79_concarpeculiar' ";
       $virgula = ",";
       if(trim($this->rh79_concarpeculiar) == null ){ 
         $this->erro_sql = " Campo Caracteristica Peculiar nao Informado.";
         $this->erro_campo = "rh79_concarpeculiar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($rh79_sequencial!=null){
       $sql .= " rh79_sequencial = $this->rh79_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh79_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14415,'$this->rh79_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh79_sequencial"]) || $this->rh79_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2541,14415,'".AddSlashes(pg_result($resaco,$conresaco,'rh79_sequencial'))."','$this->rh79_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh79_recurso"]) || $this->rh79_recurso != "")
           $resac = db_query("insert into db_acount values($acount,2541,14416,'".AddSlashes(pg_result($resaco,$conresaco,'rh79_recurso'))."','$this->rh79_recurso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh79_anousu"]) || $this->rh79_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2541,14417,'".AddSlashes(pg_result($resaco,$conresaco,'rh79_anousu'))."','$this->rh79_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh79_mesusu"]) || $this->rh79_mesusu != "")
           $resac = db_query("insert into db_acount values($acount,2541,14418,'".AddSlashes(pg_result($resaco,$conresaco,'rh79_mesusu'))."','$this->rh79_mesusu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh79_siglaarq"]) || $this->rh79_siglaarq != "")
           $resac = db_query("insert into db_acount values($acount,2541,14419,'".AddSlashes(pg_result($resaco,$conresaco,'rh79_siglaarq'))."','$this->rh79_siglaarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh79_tipoempenho"]) || $this->rh79_tipoempenho != "")
           $resac = db_query("insert into db_acount values($acount,2541,14420,'".AddSlashes(pg_result($resaco,$conresaco,'rh79_tipoempenho'))."','$this->rh79_tipoempenho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh79_tabprev"]) || $this->rh79_tabprev != "")
           $resac = db_query("insert into db_acount values($acount,2541,14421,'".AddSlashes(pg_result($resaco,$conresaco,'rh79_tabprev'))."','$this->rh79_tabprev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh79_seqcompl"]) || $this->rh79_seqcompl != "")
           $resac = db_query("insert into db_acount values($acount,2541,14422,'".AddSlashes(pg_result($resaco,$conresaco,'rh79_seqcompl'))."','$this->rh79_seqcompl',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh79_concarpeculiar"]) || $this->rh79_concarpeculiar != "")
           $resac = db_query("insert into db_acount values($acount,2541,15049,'".AddSlashes(pg_result($resaco,$conresaco,'rh79_concarpeculiar'))."','$this->rh79_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Slip Folha nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh79_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Slip Folha nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh79_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh79_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh79_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh79_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14415,'$rh79_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2541,14415,'','".AddSlashes(pg_result($resaco,$iresaco,'rh79_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2541,14416,'','".AddSlashes(pg_result($resaco,$iresaco,'rh79_recurso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2541,14417,'','".AddSlashes(pg_result($resaco,$iresaco,'rh79_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2541,14418,'','".AddSlashes(pg_result($resaco,$iresaco,'rh79_mesusu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2541,14419,'','".AddSlashes(pg_result($resaco,$iresaco,'rh79_siglaarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2541,14420,'','".AddSlashes(pg_result($resaco,$iresaco,'rh79_tipoempenho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2541,14421,'','".AddSlashes(pg_result($resaco,$iresaco,'rh79_tabprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2541,14422,'','".AddSlashes(pg_result($resaco,$iresaco,'rh79_seqcompl'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2541,15049,'','".AddSlashes(pg_result($resaco,$iresaco,'rh79_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from rhslipfolha
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh79_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh79_sequencial = $rh79_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Slip Folha nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh79_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Slip Folha nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh79_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh79_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:rhslipfolha";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $rh79_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhslipfolha ";
     $sql .= "      inner join orctiporec  on  orctiporec.o15_codigo = rhslipfolha.rh79_recurso";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = rhslipfolha.rh79_concarpeculiar";
     $sql .= "      inner join db_estruturavalor  on  db_estruturavalor.db121_sequencial = orctiporec.o15_db_estruturavalor";
     $sql2 = "";
     if($dbwhere==""){
       if($rh79_sequencial!=null ){
         $sql2 .= " where rhslipfolha.rh79_sequencial = $rh79_sequencial "; 
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
   function sql_query_file ( $rh79_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhslipfolha ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh79_sequencial!=null ){
         $sql2 .= " where rhslipfolha.rh79_sequencial = $rh79_sequencial "; 
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
  
   function sql_query_rubricas( $rh79_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhslipfolha ";
     $sql .= "      left join rhslipfolharhemprubrica on rhslipfolharhemprubrica.rh80_rhslipfolha = rhslipfolha.rh79_sequencial                        ";
     $sql .= "      left join rhempenhofolharubrica   on rhempenhofolharubrica.rh73_sequencial    = rhslipfolharhemprubrica.rh80_rhempenhofolharubrica ";
     
     $sql2 = "";
     if($dbwhere==""){
       if($rh72_sequencial!=null ){
         $sql2 .= " where rhslipfolha.rh79_sequencial = $rh79_sequencial "; 
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

  function sql_query_slip( $rh79_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from rhslipfolha ";
     $sql .= "      left  join rhslipfolhaslip               on rh82_rhslipfolha           = rh79_sequencial            ";
     $sql .= "      left  join slip                          on k17_codigo                 = rh82_slip                  ";
     $sql .= "      inner join orctiporec                    on o15_codigo                 = rh79_recurso               ";
     $sql .= "      inner join rhslipfolharhemprubrica       on rh80_rhslipfolha           = rh79_sequencial            ";
     $sql .= "      inner join rhempenhofolharubrica         on rh73_sequencial            = rh80_rhempenhofolharubrica ";
     $sql .= "      inner join rhempenhofolharubricaretencao on rh78_rhempenhofolharubrica = rh73_sequencial            ";
     $sql .= "      inner join retencaotiporec               on e21_sequencial             = rh78_retencaotiporec       ";
     $sql .= "      left  join retencaotiporeccgm            on e48_retencaotiporec        = e21_sequencial             ";
     $sql .= "      inner join tabrec                        on tabrec.k02_codigo          = e21_receita                ";
     $sql .= "      inner join tabplan                       on tabplan.k02_codigo         = tabrec.k02_codigo          ";
     $sql .= "                                              and tabplan.k02_anousu         = rh79_anousu                ";
     $sql .= "      inner join conplanoreduz                 on c61_reduz                  = k02_reduz                  ";
     $sql .= "                                              and c61_anousu                 = tabplan.k02_anousu         ";
     $sql .= "      inner join conplano                      on conplano.c60_codcon        = conplanoreduz.c61_codcon   ";
     $sql .= "                                              and c61_anousu                 = c60_anousu                 ";
     $sql .= "      left  join rhcontasrec                   on rh41_codigo                = rh79_recurso               ";
     $sql .= "                                              and rh41_anousu                = rh79_anousu                ";
     $sql .= "                                              and rh41_instit                = rh73_instit                ";
     
     $sql2 = "";
     if($dbwhere==""){
       if($rh79_sequencial!=null ){
         $sql2 .= " where rhslipfolha.rh79_sequencial = $rh79_sequencial "; 
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