<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: protocolo
//CLASSE DA ENTIDADE certidao
class cl_certidao { 
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
   var $p50_sequencial = 0; 
   var $p50_idusuario = 0; 
   var $p50_tipo = null; 
   var $p50_data_dia = null; 
   var $p50_data_mes = null; 
   var $p50_data_ano = null; 
   var $p50_data = null; 
   var $p50_hora = null; 
   var $p50_ip = null; 
   var $p50_hist = null; 
   var $p50_web = 'f'; 
   var $p50_codproc = 0; 
   var $p50_exerc = 0; 
   var $p50_codimpresso = null; 
   var $p50_instit = 0; 
   var $p50_arquivo = 0; 
   var $p50_diasvalidade = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p50_sequencial = int8 = Codigo 
                 p50_idusuario = int4 = Cod. Usuário 
                 p50_tipo = char(1) = Tipo da Certidão 
                 p50_data = date = Data de inclusão 
                 p50_hora = varchar(10) = Hora da inclusão 
                 p50_ip = varchar(16) = IP 
                 p50_hist = text = Histórico 
                 p50_web = bool = Gerado pela web 
                 p50_codproc = int4 = Código do processo 
                 p50_exerc = int4 = Exercício 
                 p50_codimpresso = varchar(20) = Código Impresso 
                 p50_instit = int4 = Cod. Instituição 
                 p50_arquivo = oid = Imagem 
                 p50_diasvalidade = int4 = Dias de validade da certidão 
                 ";
   //funcao construtor da classe 
   function cl_certidao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("certidao"); 
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
       $this->p50_sequencial = ($this->p50_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p50_sequencial"]:$this->p50_sequencial);
       $this->p50_idusuario = ($this->p50_idusuario == ""?@$GLOBALS["HTTP_POST_VARS"]["p50_idusuario"]:$this->p50_idusuario);
       $this->p50_tipo = ($this->p50_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["p50_tipo"]:$this->p50_tipo);
       if($this->p50_data == ""){
         $this->p50_data_dia = ($this->p50_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["p50_data_dia"]:$this->p50_data_dia);
         $this->p50_data_mes = ($this->p50_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["p50_data_mes"]:$this->p50_data_mes);
         $this->p50_data_ano = ($this->p50_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["p50_data_ano"]:$this->p50_data_ano);
         if($this->p50_data_dia != ""){
            $this->p50_data = $this->p50_data_ano."-".$this->p50_data_mes."-".$this->p50_data_dia;
         }
       }
       $this->p50_hora = ($this->p50_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["p50_hora"]:$this->p50_hora);
       $this->p50_ip = ($this->p50_ip == ""?@$GLOBALS["HTTP_POST_VARS"]["p50_ip"]:$this->p50_ip);
       $this->p50_hist = ($this->p50_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["p50_hist"]:$this->p50_hist);
       $this->p50_web = ($this->p50_web == "f"?@$GLOBALS["HTTP_POST_VARS"]["p50_web"]:$this->p50_web);
       $this->p50_codproc = ($this->p50_codproc == ""?@$GLOBALS["HTTP_POST_VARS"]["p50_codproc"]:$this->p50_codproc);
       $this->p50_exerc = ($this->p50_exerc == ""?@$GLOBALS["HTTP_POST_VARS"]["p50_exerc"]:$this->p50_exerc);
       $this->p50_codimpresso = ($this->p50_codimpresso == ""?@$GLOBALS["HTTP_POST_VARS"]["p50_codimpresso"]:$this->p50_codimpresso);
       $this->p50_instit = ($this->p50_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["p50_instit"]:$this->p50_instit);
       $this->p50_arquivo = ($this->p50_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["p50_arquivo"]:$this->p50_arquivo);
       $this->p50_diasvalidade = ($this->p50_diasvalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["p50_diasvalidade"]:$this->p50_diasvalidade);
     }else{
       $this->p50_sequencial = ($this->p50_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p50_sequencial"]:$this->p50_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($p50_sequencial){ 
      $this->atualizacampos();
     if($this->p50_idusuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário não informado.";
       $this->erro_campo = "p50_idusuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p50_tipo == null ){ 
       $this->erro_sql = " Campo Tipo da Certidão não informado.";
       $this->erro_campo = "p50_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p50_data == null ){ 
       $this->erro_sql = " Campo Data de inclusão não informado.";
       $this->erro_campo = "p50_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p50_hora == null ){ 
       $this->erro_sql = " Campo Hora da inclusão não informado.";
       $this->erro_campo = "p50_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p50_ip == null ){ 
       $this->erro_sql = " Campo IP não informado.";
       $this->erro_campo = "p50_ip";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p50_hist == null ){ 
       $this->erro_sql = " Campo Histórico não informado.";
       $this->erro_campo = "p50_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p50_web == null ){ 
       $this->erro_sql = " Campo Gerado pela web não informado.";
       $this->erro_campo = "p50_web";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p50_codproc == null ){ 
       $this->p50_codproc = "0";
     }
     if($this->p50_exerc == null ){ 
       $this->p50_exerc = "0";
     }
     if($this->p50_instit == null ){ 
       $this->erro_sql = " Campo Cod. Instituição não informado.";
       $this->erro_campo = "p50_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($p50_sequencial == "" || $p50_sequencial == null ){
       $result = db_query("select nextval('certidao_p50_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: certidao_p50_sequencial_seq do campo: p50_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->p50_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from certidao_p50_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $p50_sequencial)){
         $this->erro_sql = " Campo p50_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p50_sequencial = $p50_sequencial; 
       }
     }
     if(($this->p50_sequencial == null) || ($this->p50_sequencial == "") ){ 
       $this->erro_sql = " Campo p50_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into certidao(
                                       p50_sequencial 
                                      ,p50_idusuario 
                                      ,p50_tipo 
                                      ,p50_data 
                                      ,p50_hora 
                                      ,p50_ip 
                                      ,p50_hist 
                                      ,p50_web 
                                      ,p50_codproc 
                                      ,p50_exerc 
                                      ,p50_codimpresso 
                                      ,p50_instit 
                                      ,p50_arquivo 
                                      ,p50_diasvalidade 
                       )
                values (
                                $this->p50_sequencial 
                               ,$this->p50_idusuario 
                               ,'$this->p50_tipo' 
                               ,".($this->p50_data == "null" || $this->p50_data == ""?"null":"'".$this->p50_data."'")." 
                               ,'$this->p50_hora' 
                               ,'$this->p50_ip' 
                               ,'$this->p50_hist' 
                               ,'$this->p50_web' 
                               ,$this->p50_codproc 
                               ,$this->p50_exerc 
                               ,'$this->p50_codimpresso' 
                               ,$this->p50_instit 
                               ,$this->p50_arquivo 
                               ,$this->p50_diasvalidade 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Certidoes geradas ($this->p50_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Certidoes geradas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Certidoes geradas ($this->p50_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p50_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p50_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8653,'$this->p50_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,1475,8653,'','".AddSlashes(pg_result($resaco,0,'p50_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1475,8657,'','".AddSlashes(pg_result($resaco,0,'p50_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1475,8659,'','".AddSlashes(pg_result($resaco,0,'p50_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1475,8654,'','".AddSlashes(pg_result($resaco,0,'p50_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1475,8656,'','".AddSlashes(pg_result($resaco,0,'p50_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1475,8658,'','".AddSlashes(pg_result($resaco,0,'p50_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1475,8661,'','".AddSlashes(pg_result($resaco,0,'p50_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1475,8660,'','".AddSlashes(pg_result($resaco,0,'p50_web'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1475,9416,'','".AddSlashes(pg_result($resaco,0,'p50_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1475,9417,'','".AddSlashes(pg_result($resaco,0,'p50_exerc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1475,9418,'','".AddSlashes(pg_result($resaco,0,'p50_codimpresso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1475,10676,'','".AddSlashes(pg_result($resaco,0,'p50_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1475,20231,'','".AddSlashes(pg_result($resaco,0,'p50_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1475,20243,'','".AddSlashes(pg_result($resaco,0,'p50_diasvalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p50_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update certidao set ";
     $virgula = "";
     if(trim($this->p50_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p50_sequencial"])){ 
       $sql  .= $virgula." p50_sequencial = $this->p50_sequencial ";
       $virgula = ",";
       if(trim($this->p50_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo não informado.";
         $this->erro_campo = "p50_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p50_idusuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p50_idusuario"])){ 
       $sql  .= $virgula." p50_idusuario = $this->p50_idusuario ";
       $virgula = ",";
       if(trim($this->p50_idusuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário não informado.";
         $this->erro_campo = "p50_idusuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p50_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p50_tipo"])){ 
       $sql  .= $virgula." p50_tipo = '$this->p50_tipo' ";
       $virgula = ",";
       if(trim($this->p50_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo da Certidão não informado.";
         $this->erro_campo = "p50_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p50_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p50_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["p50_data_dia"] !="") ){ 
       $sql  .= $virgula." p50_data = '$this->p50_data' ";
       $virgula = ",";
       if(trim($this->p50_data) == null ){ 
         $this->erro_sql = " Campo Data de inclusão não informado.";
         $this->erro_campo = "p50_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["p50_data_dia"])){ 
         $sql  .= $virgula." p50_data = null ";
         $virgula = ",";
         if(trim($this->p50_data) == null ){ 
           $this->erro_sql = " Campo Data de inclusão não informado.";
           $this->erro_campo = "p50_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->p50_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p50_hora"])){ 
       $sql  .= $virgula." p50_hora = '$this->p50_hora' ";
       $virgula = ",";
       if(trim($this->p50_hora) == null ){ 
         $this->erro_sql = " Campo Hora da inclusão não informado.";
         $this->erro_campo = "p50_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p50_ip)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p50_ip"])){ 
       $sql  .= $virgula." p50_ip = '$this->p50_ip' ";
       $virgula = ",";
       if(trim($this->p50_ip) == null ){ 
         $this->erro_sql = " Campo IP não informado.";
         $this->erro_campo = "p50_ip";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p50_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p50_hist"])){ 
       $sql  .= $virgula." p50_hist = '$this->p50_hist' ";
       $virgula = ",";
       if(trim($this->p50_hist) == null ){ 
         $this->erro_sql = " Campo Histórico não informado.";
         $this->erro_campo = "p50_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p50_web)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p50_web"])){ 
       $sql  .= $virgula." p50_web = '$this->p50_web' ";
       $virgula = ",";
       if(trim($this->p50_web) == null ){ 
         $this->erro_sql = " Campo Gerado pela web não informado.";
         $this->erro_campo = "p50_web";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p50_codproc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p50_codproc"])){ 
        if(trim($this->p50_codproc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["p50_codproc"])){ 
           $this->p50_codproc = "0" ; 
        } 
       $sql  .= $virgula." p50_codproc = $this->p50_codproc ";
       $virgula = ",";
     }
     if(trim($this->p50_exerc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p50_exerc"])){ 
        if(trim($this->p50_exerc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["p50_exerc"])){ 
           $this->p50_exerc = "0" ; 
        } 
       $sql  .= $virgula." p50_exerc = $this->p50_exerc ";
       $virgula = ",";
     }
     if(trim($this->p50_codimpresso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p50_codimpresso"])){ 
       $sql  .= $virgula." p50_codimpresso = '$this->p50_codimpresso' ";
       $virgula = ",";
     }
     if(trim($this->p50_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p50_instit"])){ 
       $sql  .= $virgula." p50_instit = $this->p50_instit ";
       $virgula = ",";
       if(trim($this->p50_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição não informado.";
         $this->erro_campo = "p50_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p50_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p50_arquivo"])){ 
       $sql  .= $virgula." p50_arquivo = $this->p50_arquivo ";
       $virgula = ",";
     }
     if(trim($this->p50_diasvalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p50_diasvalidade"])){ 
       $sql  .= $virgula." p50_diasvalidade = $this->p50_diasvalidade ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($p50_sequencial!=null){
       $sql .= " p50_sequencial = $this->p50_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->p50_sequencial));
       if($this->numrows>0){

         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,8653,'$this->p50_sequencial','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p50_sequencial"]) || $this->p50_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,1475,8653,'".AddSlashes(pg_result($resaco,$conresaco,'p50_sequencial'))."','$this->p50_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p50_idusuario"]) || $this->p50_idusuario != "")
             $resac = db_query("insert into db_acount values($acount,1475,8657,'".AddSlashes(pg_result($resaco,$conresaco,'p50_idusuario'))."','$this->p50_idusuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p50_tipo"]) || $this->p50_tipo != "")
             $resac = db_query("insert into db_acount values($acount,1475,8659,'".AddSlashes(pg_result($resaco,$conresaco,'p50_tipo'))."','$this->p50_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p50_data"]) || $this->p50_data != "")
             $resac = db_query("insert into db_acount values($acount,1475,8654,'".AddSlashes(pg_result($resaco,$conresaco,'p50_data'))."','$this->p50_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p50_hora"]) || $this->p50_hora != "")
             $resac = db_query("insert into db_acount values($acount,1475,8656,'".AddSlashes(pg_result($resaco,$conresaco,'p50_hora'))."','$this->p50_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p50_ip"]) || $this->p50_ip != "")
             $resac = db_query("insert into db_acount values($acount,1475,8658,'".AddSlashes(pg_result($resaco,$conresaco,'p50_ip'))."','$this->p50_ip',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p50_hist"]) || $this->p50_hist != "")
             $resac = db_query("insert into db_acount values($acount,1475,8661,'".AddSlashes(pg_result($resaco,$conresaco,'p50_hist'))."','$this->p50_hist',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p50_web"]) || $this->p50_web != "")
             $resac = db_query("insert into db_acount values($acount,1475,8660,'".AddSlashes(pg_result($resaco,$conresaco,'p50_web'))."','$this->p50_web',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p50_codproc"]) || $this->p50_codproc != "")
             $resac = db_query("insert into db_acount values($acount,1475,9416,'".AddSlashes(pg_result($resaco,$conresaco,'p50_codproc'))."','$this->p50_codproc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p50_exerc"]) || $this->p50_exerc != "")
             $resac = db_query("insert into db_acount values($acount,1475,9417,'".AddSlashes(pg_result($resaco,$conresaco,'p50_exerc'))."','$this->p50_exerc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p50_codimpresso"]) || $this->p50_codimpresso != "")
             $resac = db_query("insert into db_acount values($acount,1475,9418,'".AddSlashes(pg_result($resaco,$conresaco,'p50_codimpresso'))."','$this->p50_codimpresso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p50_instit"]) || $this->p50_instit != "")
             $resac = db_query("insert into db_acount values($acount,1475,10676,'".AddSlashes(pg_result($resaco,$conresaco,'p50_instit'))."','$this->p50_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p50_arquivo"]) || $this->p50_arquivo != "")
             $resac = db_query("insert into db_acount values($acount,1475,20231,'".AddSlashes(pg_result($resaco,$conresaco,'p50_arquivo'))."','$this->p50_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["p50_diasvalidade"]) || $this->p50_diasvalidade != "")
             $resac = db_query("insert into db_acount values($acount,1475,20243,'".AddSlashes(pg_result($resaco,$conresaco,'p50_diasvalidade'))."','$this->p50_diasvalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Certidoes geradas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p50_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Certidoes geradas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p50_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p50_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p50_sequencial=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if ($dbwhere==null || $dbwhere=="") {

         $resaco = $this->sql_record($this->sql_query_file($p50_sequencial));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,8653,'$p50_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,1475,8653,'','".AddSlashes(pg_result($resaco,$iresaco,'p50_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1475,8657,'','".AddSlashes(pg_result($resaco,$iresaco,'p50_idusuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1475,8659,'','".AddSlashes(pg_result($resaco,$iresaco,'p50_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1475,8654,'','".AddSlashes(pg_result($resaco,$iresaco,'p50_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1475,8656,'','".AddSlashes(pg_result($resaco,$iresaco,'p50_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1475,8658,'','".AddSlashes(pg_result($resaco,$iresaco,'p50_ip'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1475,8661,'','".AddSlashes(pg_result($resaco,$iresaco,'p50_hist'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1475,8660,'','".AddSlashes(pg_result($resaco,$iresaco,'p50_web'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1475,9416,'','".AddSlashes(pg_result($resaco,$iresaco,'p50_codproc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1475,9417,'','".AddSlashes(pg_result($resaco,$iresaco,'p50_exerc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1475,9418,'','".AddSlashes(pg_result($resaco,$iresaco,'p50_codimpresso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1475,10676,'','".AddSlashes(pg_result($resaco,$iresaco,'p50_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1475,20231,'','".AddSlashes(pg_result($resaco,$iresaco,'p50_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1475,20243,'','".AddSlashes(pg_result($resaco,$iresaco,'p50_diasvalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from certidao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p50_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p50_sequencial = $p50_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Certidoes geradas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p50_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Certidoes geradas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p50_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p50_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:certidao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $p50_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from certidao ";
     $sql .= "      inner join db_config  on  db_config.codigo = certidao.p50_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = certidao.p50_idusuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql .= "      inner join db_tipoinstit  on  db_tipoinstit.db21_codtipo = db_config.db21_tipoinstit";
     $sql2 = "";
     if($dbwhere==""){
       if($p50_sequencial!=null ){
         $sql2 .= " where certidao.p50_sequencial = $p50_sequencial "; 
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
   function sql_query_file ( $p50_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from certidao ";
     $sql2 = "";
     if($dbwhere==""){
       if($p50_sequencial!=null ){
         $sql2 .= " where certidao.p50_sequencial = $p50_sequencial "; 
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
  
   function sql_query_certidao ( $p50_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from certidao ";
     $sql .= "      inner join db_config  on  db_config.codigo = certidao.p50_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = certidao.p50_idusuario";
     $sql .= "      left join certidaocgm on p50_sequencial = p49_sequencial";
     $sql .= "      left join certidaoinscr on p50_sequencial = p48_sequencial";
     $sql .= "      left join certidaomatric on p50_sequencial = p47_sequencial ";
     $sql .= "      left join iptuant on p47_matric = j40_matric ";
     $sql .= "      left join cgm  on  cgm.z01_numcgm = certidaocgm.p49_numcgm";
     $sql2 = "";
     if($dbwhere==""){ 
       if($p50_sequencial!=null ){
         $sql2 .= " where certidao.p50_sequencial = $p50_sequencial ";
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
   * Método que retorna as certidões e seus prazos vigentes
   * @param string $sOrigem
   * @param integer $iCodigoOrigem
   * @param string $sDataValidaEmissao
   * @return string
   */
  function sql_query_certidao_prazos ( $sOrigem, $iCodigoOrigem, $sDataValidaEmissao = null ) {
  	
  	$sWhere = '';
  	
  	if( empty( $sDataValidaEmissao ) ){
  		$sDataValidaEmissao = date('Y-m-d') ;
  	}
  	
  	if( $sOrigem == 'I' ){
  		$sWhere = "p48_inscr  = {$iCodigoOrigem} ";
  	}elseif ( $sOrigem == 'M' ){
  		$sWhere = "p47_matric = {$iCodigoOrigem} ";
  	}else{
  		$sWhere = "p49_numcgm = {$iCodigoOrigem} ";
  	}
  	
  	$sSql  = "  select p50_sequencial                                                        as nro,                                         ";
  	$sSql .= "         p50_tipo                                                              as tipo_certidao,                               ";
  	$sSql .= "         p50_data                                                              as data_emissao,                                ";
  	$sSql .= "         p50_hora                                                              as hora_emissao,                                ";
  	$sSql .= "         case                                                                                                                  ";
    $sSql .= "             when p50_diasvalidade - k03_diasreemissaocertidao <= 0 then now()::date                                           ";
    $sSql .= "             else p50_data::date + p50_diasvalidade                                                                            ";
    $sSql .= "         end AS data_validade,                                                                                                 ";
  	$sSql .= "         case                                                                                                                  ";
    $sSql .= "             when p50_diasvalidade - k03_diasreemissaocertidao <= 0 then now()::date                                           ";
    $sSql .= "             else p50_data::date + p50_diasvalidade - k03_diasreemissaocertidao                                                ";
    $sSql .= "         end AS prazo_reemissao,                                                                                               ";
  	$sSql .= "                                                                                                                               ";
  	$sSql .= "         case                                                                                                                  ";
  	$sSql .= "             when p48_sequencial is not null then 'I - '||p48_inscr                                                            ";
  	$sSql .= "             when p47_sequencial is not null then 'M - '||p47_matric                                                           ";
  	$sSql .= "             else 'C - '||p49_numcgm                                                                                           ";
  	$sSql .= "          end                                                                  as origem,                                      ";
  	$sSql .= "         nome                                                                  as emissor,                                     ";
  	$sSql .= "         p50_web                                                               as emissao_dbpref,                              ";
  	$sSql .= "                                                                                                                               ";
  	$sSql .= "         case                                                                                                                  ";
  	$sSql .= "				 when (p50_data::date = now()::date) then true					                                                               ";
  	$sSql .= "         when ( ( p50_data::date + p50_diasvalidade - k03_diasreemissaocertidao) - '$sDataValidaEmissao'::date) < 0 then false ";
  	$sSql .= "         else true                                                                                                             ";
  	$sSql .= "         end                                                                   as habilita_reemissao                           ";
  	$sSql .= "    from certidao                                                                                                              ";
  	$sSql .= "         inner join db_config     on db_config.codigo             = certidao.p50_instit                                        ";
  	$sSql .= "         inner join numpref       on certidao.p50_instit          = k03_instit                                                 ";
  	$sSql .= "                                 and extract( year from p50_data) = k03_anousu                                                 ";
  	$sSql .= "         inner join db_usuarios   on db_usuarios.id_usuario       = certidao.p50_idusuario                                     ";
  	$sSql .= "         left join certidaocgm    on p50_sequencial               = p49_sequencial                                             ";
  	$sSql .= "         left join certidaoinscr  on p50_sequencial               = p48_sequencial                                             ";
  	$sSql .= "         left join certidaomatric on p50_sequencial               = p47_sequencial                                             ";
  	$sSql .= "         left join iptuant        on p47_matric                   = j40_matric                                                 ";
  	$sSql .= "         left join cgm            on  cgm.z01_numcgm              = certidaocgm.p49_numcgm                                     ";
  	$sSql .= "   where $sWhere																																																							 ";
  	$sSql .= "order by p50_sequencial desc                                                                                                   ";
  	
  	return $sSql;
  }
}
?>