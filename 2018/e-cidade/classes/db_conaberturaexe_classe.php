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

//MODULO: contabilidade
//CLASSE DA ENTIDADE conaberturaexe
class cl_conaberturaexe { 
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
   var $c91_sequencial = 0; 
   var $c91_instit = 0; 
   var $c91_id_usuario = 0; 
   var $c91_anousuorigem = 0; 
   var $c91_anousudestino = 0; 
   var $c91_data_dia = null; 
   var $c91_data_mes = null; 
   var $c91_data_ano = null; 
   var $c91_data = null; 
   var $c91_hora = null; 
   var $c91_situacao = 0; 
   var $c91_tipo = 0; 
   var $c91_ppa = 0; 
   var $c91_origem = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 c91_sequencial = int4 = Sequencial 
                 c91_instit = int4 = Insitituição 
                 c91_id_usuario = int4 = Usuário 
                 c91_anousuorigem = int4 = Ano de Origem 
                 c91_anousudestino = int4 = Ano de Destino 
                 c91_data = date = Data de Criação 
                 c91_hora = char(5) = Hora 
                 c91_situacao = int4 = Situação 
                 c91_tipo = int4 = TIpo da Importação 
                 c91_ppa = int4 = PPA 
                 c91_origem = int4 = Origem 
                 ";
   //funcao construtor da classe 
   function cl_conaberturaexe() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("conaberturaexe"); 
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
       $this->c91_sequencial = ($this->c91_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c91_sequencial"]:$this->c91_sequencial);
       $this->c91_instit = ($this->c91_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["c91_instit"]:$this->c91_instit);
       $this->c91_id_usuario = ($this->c91_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["c91_id_usuario"]:$this->c91_id_usuario);
       $this->c91_anousuorigem = ($this->c91_anousuorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["c91_anousuorigem"]:$this->c91_anousuorigem);
       $this->c91_anousudestino = ($this->c91_anousudestino == ""?@$GLOBALS["HTTP_POST_VARS"]["c91_anousudestino"]:$this->c91_anousudestino);
       if($this->c91_data == ""){
         $this->c91_data_dia = ($this->c91_data_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["c91_data_dia"]:$this->c91_data_dia);
         $this->c91_data_mes = ($this->c91_data_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["c91_data_mes"]:$this->c91_data_mes);
         $this->c91_data_ano = ($this->c91_data_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["c91_data_ano"]:$this->c91_data_ano);
         if($this->c91_data_dia != ""){
            $this->c91_data = $this->c91_data_ano."-".$this->c91_data_mes."-".$this->c91_data_dia;
         }
       }
       $this->c91_hora = ($this->c91_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["c91_hora"]:$this->c91_hora);
       $this->c91_situacao = ($this->c91_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["c91_situacao"]:$this->c91_situacao);
       $this->c91_tipo = ($this->c91_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["c91_tipo"]:$this->c91_tipo);
       $this->c91_ppa = ($this->c91_ppa == ""?@$GLOBALS["HTTP_POST_VARS"]["c91_ppa"]:$this->c91_ppa);
       $this->c91_origem = ($this->c91_origem == ""?@$GLOBALS["HTTP_POST_VARS"]["c91_origem"]:$this->c91_origem);
     }else{
       $this->c91_sequencial = ($this->c91_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["c91_sequencial"]:$this->c91_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($c91_sequencial){ 
      $this->atualizacampos();
     if($this->c91_instit == null ){ 
       $this->erro_sql = " Campo Insitituição nao Informado.";
       $this->erro_campo = "c91_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c91_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "c91_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c91_anousuorigem == null ){ 
       $this->erro_sql = " Campo Ano de Origem nao Informado.";
       $this->erro_campo = "c91_anousuorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c91_anousudestino == null ){ 
       $this->erro_sql = " Campo Ano de Destino nao Informado.";
       $this->erro_campo = "c91_anousudestino";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c91_data == null ){ 
       $this->erro_sql = " Campo Data de Criação nao Informado.";
       $this->erro_campo = "c91_data_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c91_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "c91_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c91_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "c91_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c91_tipo == null ){ 
       $this->erro_sql = " Campo TIpo da Importação nao Informado.";
       $this->erro_campo = "c91_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c91_ppa == null ){ 
       $this->erro_sql = " Campo PPA nao Informado.";
       $this->erro_campo = "c91_ppa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->c91_origem == null ){ 
       $this->erro_sql = " Campo Origem nao Informado.";
       $this->erro_campo = "c91_origem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($c91_sequencial == "" || $c91_sequencial == null ){
       $result = db_query("select nextval('conaberturaexe_c91_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: conaberturaexe_c91_sequencial_seq do campo: c91_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->c91_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from conaberturaexe_c91_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $c91_sequencial)){
         $this->erro_sql = " Campo c91_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->c91_sequencial = $c91_sequencial; 
       }
     }
     if(($this->c91_sequencial == null) || ($this->c91_sequencial == "") ){ 
       $this->erro_sql = " Campo c91_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into conaberturaexe(
                                       c91_sequencial 
                                      ,c91_instit 
                                      ,c91_id_usuario 
                                      ,c91_anousuorigem 
                                      ,c91_anousudestino 
                                      ,c91_data 
                                      ,c91_hora 
                                      ,c91_situacao 
                                      ,c91_tipo 
                                      ,c91_ppa 
                                      ,c91_origem 
                       )
                values (
                                $this->c91_sequencial 
                               ,$this->c91_instit 
                               ,$this->c91_id_usuario 
                               ,$this->c91_anousuorigem 
                               ,$this->c91_anousudestino 
                               ,".($this->c91_data == "null" || $this->c91_data == ""?"null":"'".$this->c91_data."'")." 
                               ,'$this->c91_hora' 
                               ,$this->c91_situacao 
                               ,$this->c91_tipo 
                               ,$this->c91_ppa 
                               ,$this->c91_origem 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Abertura do Exercício ($this->c91_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Abertura do Exercício já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Abertura do Exercício ($this->c91_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c91_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->c91_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,10450,'$this->c91_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1806,10450,'','".AddSlashes(pg_result($resaco,0,'c91_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1806,10451,'','".AddSlashes(pg_result($resaco,0,'c91_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1806,10452,'','".AddSlashes(pg_result($resaco,0,'c91_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1806,10453,'','".AddSlashes(pg_result($resaco,0,'c91_anousuorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1806,10454,'','".AddSlashes(pg_result($resaco,0,'c91_anousudestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1806,10456,'','".AddSlashes(pg_result($resaco,0,'c91_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1806,10458,'','".AddSlashes(pg_result($resaco,0,'c91_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1806,10459,'','".AddSlashes(pg_result($resaco,0,'c91_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1806,10460,'','".AddSlashes(pg_result($resaco,0,'c91_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1806,10461,'','".AddSlashes(pg_result($resaco,0,'c91_ppa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1806,10462,'','".AddSlashes(pg_result($resaco,0,'c91_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($c91_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update conaberturaexe set ";
     $virgula = "";
     if(trim($this->c91_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c91_sequencial"])){ 
       $sql  .= $virgula." c91_sequencial = $this->c91_sequencial ";
       $virgula = ",";
       if(trim($this->c91_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "c91_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c91_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c91_instit"])){ 
       $sql  .= $virgula." c91_instit = $this->c91_instit ";
       $virgula = ",";
       if(trim($this->c91_instit) == null ){ 
         $this->erro_sql = " Campo Insitituição nao Informado.";
         $this->erro_campo = "c91_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c91_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c91_id_usuario"])){ 
       $sql  .= $virgula." c91_id_usuario = $this->c91_id_usuario ";
       $virgula = ",";
       if(trim($this->c91_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "c91_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c91_anousuorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c91_anousuorigem"])){ 
       $sql  .= $virgula." c91_anousuorigem = $this->c91_anousuorigem ";
       $virgula = ",";
       if(trim($this->c91_anousuorigem) == null ){ 
         $this->erro_sql = " Campo Ano de Origem nao Informado.";
         $this->erro_campo = "c91_anousuorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c91_anousudestino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c91_anousudestino"])){ 
       $sql  .= $virgula." c91_anousudestino = $this->c91_anousudestino ";
       $virgula = ",";
       if(trim($this->c91_anousudestino) == null ){ 
         $this->erro_sql = " Campo Ano de Destino nao Informado.";
         $this->erro_campo = "c91_anousudestino";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c91_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c91_data_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["c91_data_dia"] !="") ){ 
       $sql  .= $virgula." c91_data = '$this->c91_data' ";
       $virgula = ",";
       if(trim($this->c91_data) == null ){ 
         $this->erro_sql = " Campo Data de Criação nao Informado.";
         $this->erro_campo = "c91_data_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["c91_data_dia"])){ 
         $sql  .= $virgula." c91_data = null ";
         $virgula = ",";
         if(trim($this->c91_data) == null ){ 
           $this->erro_sql = " Campo Data de Criação nao Informado.";
           $this->erro_campo = "c91_data_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->c91_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c91_hora"])){ 
       $sql  .= $virgula." c91_hora = '$this->c91_hora' ";
       $virgula = ",";
       if(trim($this->c91_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "c91_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c91_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c91_situacao"])){ 
       $sql  .= $virgula." c91_situacao = $this->c91_situacao ";
       $virgula = ",";
       if(trim($this->c91_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "c91_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c91_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c91_tipo"])){ 
       $sql  .= $virgula." c91_tipo = $this->c91_tipo ";
       $virgula = ",";
       if(trim($this->c91_tipo) == null ){ 
         $this->erro_sql = " Campo TIpo da Importação nao Informado.";
         $this->erro_campo = "c91_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c91_ppa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c91_ppa"])){ 
       $sql  .= $virgula." c91_ppa = $this->c91_ppa ";
       $virgula = ",";
       if(trim($this->c91_ppa) == null ){ 
         $this->erro_sql = " Campo PPA nao Informado.";
         $this->erro_campo = "c91_ppa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->c91_origem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["c91_origem"])){ 
       $sql  .= $virgula." c91_origem = $this->c91_origem ";
       $virgula = ",";
       if(trim($this->c91_origem) == null ){ 
         $this->erro_sql = " Campo Origem nao Informado.";
         $this->erro_campo = "c91_origem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($c91_sequencial!=null){
       $sql .= " c91_sequencial = $this->c91_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->c91_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10450,'$this->c91_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c91_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1806,10450,'".AddSlashes(pg_result($resaco,$conresaco,'c91_sequencial'))."','$this->c91_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c91_instit"]))
           $resac = db_query("insert into db_acount values($acount,1806,10451,'".AddSlashes(pg_result($resaco,$conresaco,'c91_instit'))."','$this->c91_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c91_id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1806,10452,'".AddSlashes(pg_result($resaco,$conresaco,'c91_id_usuario'))."','$this->c91_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c91_anousuorigem"]))
           $resac = db_query("insert into db_acount values($acount,1806,10453,'".AddSlashes(pg_result($resaco,$conresaco,'c91_anousuorigem'))."','$this->c91_anousuorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c91_anousudestino"]))
           $resac = db_query("insert into db_acount values($acount,1806,10454,'".AddSlashes(pg_result($resaco,$conresaco,'c91_anousudestino'))."','$this->c91_anousudestino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c91_data"]))
           $resac = db_query("insert into db_acount values($acount,1806,10456,'".AddSlashes(pg_result($resaco,$conresaco,'c91_data'))."','$this->c91_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c91_hora"]))
           $resac = db_query("insert into db_acount values($acount,1806,10458,'".AddSlashes(pg_result($resaco,$conresaco,'c91_hora'))."','$this->c91_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c91_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1806,10459,'".AddSlashes(pg_result($resaco,$conresaco,'c91_situacao'))."','$this->c91_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c91_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1806,10460,'".AddSlashes(pg_result($resaco,$conresaco,'c91_tipo'))."','$this->c91_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c91_ppa"]))
           $resac = db_query("insert into db_acount values($acount,1806,10461,'".AddSlashes(pg_result($resaco,$conresaco,'c91_ppa'))."','$this->c91_ppa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["c91_origem"]))
           $resac = db_query("insert into db_acount values($acount,1806,10462,'".AddSlashes(pg_result($resaco,$conresaco,'c91_origem'))."','$this->c91_origem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Abertura do Exercício nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->c91_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Abertura do Exercício nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->c91_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->c91_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($c91_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($c91_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,10450,'$c91_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1806,10450,'','".AddSlashes(pg_result($resaco,$iresaco,'c91_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1806,10451,'','".AddSlashes(pg_result($resaco,$iresaco,'c91_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1806,10452,'','".AddSlashes(pg_result($resaco,$iresaco,'c91_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1806,10453,'','".AddSlashes(pg_result($resaco,$iresaco,'c91_anousuorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1806,10454,'','".AddSlashes(pg_result($resaco,$iresaco,'c91_anousudestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1806,10456,'','".AddSlashes(pg_result($resaco,$iresaco,'c91_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1806,10458,'','".AddSlashes(pg_result($resaco,$iresaco,'c91_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1806,10459,'','".AddSlashes(pg_result($resaco,$iresaco,'c91_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1806,10460,'','".AddSlashes(pg_result($resaco,$iresaco,'c91_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1806,10461,'','".AddSlashes(pg_result($resaco,$iresaco,'c91_ppa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1806,10462,'','".AddSlashes(pg_result($resaco,$iresaco,'c91_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from conaberturaexe
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($c91_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " c91_sequencial = $c91_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Abertura do Exercício nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$c91_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Abertura do Exercício nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$c91_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$c91_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:conaberturaexe";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $c91_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conaberturaexe ";
     $sql .= "      inner join db_config  on  db_config.codigo = conaberturaexe.c91_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = conaberturaexe.c91_id_usuario";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($c91_sequencial!=null ){
         $sql2 .= " where conaberturaexe.c91_sequencial = $c91_sequencial "; 
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
   function sql_query_file ( $c91_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from conaberturaexe ";
     $sql2 = "";
     if($dbwhere==""){
       if($c91_sequencial!=null ){
         $sql2 .= " where conaberturaexe.c91_sequencial = $c91_sequencial "; 
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