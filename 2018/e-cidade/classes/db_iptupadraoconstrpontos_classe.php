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

//MODULO: Cadastro
//CLASSE DA ENTIDADE iptupadraoconstrpontos
class cl_iptupadraoconstrpontos { 
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
   var $j118_sequencial = 0; 
   var $j118_iptupadraoconstr = 0; 
   var $j118_caracter = 0; 
   var $j118_pontosini = 0; 
   var $j118_pontosfim = 0; 
   var $j118_fatorreajuste = 0; 
   var $j118_anousu = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j118_sequencial = int4 = Sequencial 
                 j118_iptupadraoconstr = int4 = Iptu Padrão Construção 
                 j118_caracter = int4 = Caracter 
                 j118_pontosini = float8 = Ponto Inicial 
                 j118_pontosfim = float8 = Ponto Final 
                 j118_fatorreajuste = float8 = Fator Reajuste 
                 j118_anousu = int4 = Ano 
                 ";
   //funcao construtor da classe 
   function cl_iptupadraoconstrpontos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptupadraoconstrpontos"); 
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
       $this->j118_sequencial = ($this->j118_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j118_sequencial"]:$this->j118_sequencial);
       $this->j118_iptupadraoconstr = ($this->j118_iptupadraoconstr == ""?@$GLOBALS["HTTP_POST_VARS"]["j118_iptupadraoconstr"]:$this->j118_iptupadraoconstr);
       $this->j118_caracter = ($this->j118_caracter == ""?@$GLOBALS["HTTP_POST_VARS"]["j118_caracter"]:$this->j118_caracter);
       $this->j118_pontosini = ($this->j118_pontosini == ""?@$GLOBALS["HTTP_POST_VARS"]["j118_pontosini"]:$this->j118_pontosini);
       $this->j118_pontosfim = ($this->j118_pontosfim == ""?@$GLOBALS["HTTP_POST_VARS"]["j118_pontosfim"]:$this->j118_pontosfim);
       $this->j118_fatorreajuste = ($this->j118_fatorreajuste == ""?@$GLOBALS["HTTP_POST_VARS"]["j118_fatorreajuste"]:$this->j118_fatorreajuste);
       $this->j118_anousu = ($this->j118_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j118_anousu"]:$this->j118_anousu);
     }else{
       $this->j118_sequencial = ($this->j118_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j118_sequencial"]:$this->j118_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j118_sequencial){ 
      $this->atualizacampos();
     if($this->j118_iptupadraoconstr == null ){ 
       $this->erro_sql = " Campo Iptu Padrão Construção nao Informado.";
       $this->erro_campo = "j118_iptupadraoconstr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j118_caracter == null ){ 
       $this->erro_sql = " Campo Caracter nao Informado.";
       $this->erro_campo = "j118_caracter";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j118_pontosini == null ){ 
       $this->erro_sql = " Campo Ponto Inicial nao Informado.";
       $this->erro_campo = "j118_pontosini";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j118_pontosfim == null ){ 
       $this->erro_sql = " Campo Ponto Final nao Informado.";
       $this->erro_campo = "j118_pontosfim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j118_fatorreajuste == null ){ 
       $this->erro_sql = " Campo Fator Reajuste nao Informado.";
       $this->erro_campo = "j118_fatorreajuste";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j118_anousu == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "j118_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j118_sequencial == "" || $j118_sequencial == null ){
       $result = db_query("select nextval('iptupadraoconstrpontos_j118_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptupadraoconstrpontos_j118_sequencial_seq do campo: j118_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j118_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from iptupadraoconstrpontos_j118_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j118_sequencial)){
         $this->erro_sql = " Campo j118_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j118_sequencial = $j118_sequencial; 
       }
     }
     if(($this->j118_sequencial == null) || ($this->j118_sequencial == "") ){ 
       $this->erro_sql = " Campo j118_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptupadraoconstrpontos(
                                       j118_sequencial 
                                      ,j118_iptupadraoconstr 
                                      ,j118_caracter 
                                      ,j118_pontosini 
                                      ,j118_pontosfim 
                                      ,j118_fatorreajuste 
                                      ,j118_anousu 
                       )
                values (
                                $this->j118_sequencial 
                               ,$this->j118_iptupadraoconstr 
                               ,$this->j118_caracter 
                               ,$this->j118_pontosini 
                               ,$this->j118_pontosfim 
                               ,$this->j118_fatorreajuste 
                               ,$this->j118_anousu 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Iptu Padrão Construção Ponto ($this->j118_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Iptu Padrão Construção Ponto já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Iptu Padrão Construção Ponto ($this->j118_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j118_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j118_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15175,'$this->j118_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2673,15175,'','".AddSlashes(pg_result($resaco,0,'j118_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2673,15176,'','".AddSlashes(pg_result($resaco,0,'j118_iptupadraoconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2673,15177,'','".AddSlashes(pg_result($resaco,0,'j118_caracter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2673,15178,'','".AddSlashes(pg_result($resaco,0,'j118_pontosini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2673,15179,'','".AddSlashes(pg_result($resaco,0,'j118_pontosfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2673,15180,'','".AddSlashes(pg_result($resaco,0,'j118_fatorreajuste'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2673,15181,'','".AddSlashes(pg_result($resaco,0,'j118_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j118_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update iptupadraoconstrpontos set ";
     $virgula = "";
     if(trim($this->j118_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j118_sequencial"])){ 
       $sql  .= $virgula." j118_sequencial = $this->j118_sequencial ";
       $virgula = ",";
       if(trim($this->j118_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "j118_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j118_iptupadraoconstr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j118_iptupadraoconstr"])){ 
       $sql  .= $virgula." j118_iptupadraoconstr = $this->j118_iptupadraoconstr ";
       $virgula = ",";
       if(trim($this->j118_iptupadraoconstr) == null ){ 
         $this->erro_sql = " Campo Iptu Padrão Construção nao Informado.";
         $this->erro_campo = "j118_iptupadraoconstr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j118_caracter)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j118_caracter"])){ 
       $sql  .= $virgula." j118_caracter = $this->j118_caracter ";
       $virgula = ",";
       if(trim($this->j118_caracter) == null ){ 
         $this->erro_sql = " Campo Caracter nao Informado.";
         $this->erro_campo = "j118_caracter";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j118_pontosini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j118_pontosini"])){ 
       $sql  .= $virgula." j118_pontosini = $this->j118_pontosini ";
       $virgula = ",";
       if(trim($this->j118_pontosini) == null ){ 
         $this->erro_sql = " Campo Ponto Inicial nao Informado.";
         $this->erro_campo = "j118_pontosini";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j118_pontosfim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j118_pontosfim"])){ 
       $sql  .= $virgula." j118_pontosfim = $this->j118_pontosfim ";
       $virgula = ",";
       if(trim($this->j118_pontosfim) == null ){ 
         $this->erro_sql = " Campo Ponto Final nao Informado.";
         $this->erro_campo = "j118_pontosfim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j118_fatorreajuste)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j118_fatorreajuste"])){ 
       $sql  .= $virgula." j118_fatorreajuste = $this->j118_fatorreajuste ";
       $virgula = ",";
       if(trim($this->j118_fatorreajuste) == null ){ 
         $this->erro_sql = " Campo Fator Reajuste nao Informado.";
         $this->erro_campo = "j118_fatorreajuste";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j118_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j118_anousu"])){ 
       $sql  .= $virgula." j118_anousu = $this->j118_anousu ";
       $virgula = ",";
       if(trim($this->j118_anousu) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "j118_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j118_sequencial!=null){
       $sql .= " j118_sequencial = $this->j118_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j118_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15175,'$this->j118_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j118_sequencial"]) || $this->j118_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2673,15175,'".AddSlashes(pg_result($resaco,$conresaco,'j118_sequencial'))."','$this->j118_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j118_iptupadraoconstr"]) || $this->j118_iptupadraoconstr != "")
           $resac = db_query("insert into db_acount values($acount,2673,15176,'".AddSlashes(pg_result($resaco,$conresaco,'j118_iptupadraoconstr'))."','$this->j118_iptupadraoconstr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j118_caracter"]) || $this->j118_caracter != "")
           $resac = db_query("insert into db_acount values($acount,2673,15177,'".AddSlashes(pg_result($resaco,$conresaco,'j118_caracter'))."','$this->j118_caracter',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j118_pontosini"]) || $this->j118_pontosini != "")
           $resac = db_query("insert into db_acount values($acount,2673,15178,'".AddSlashes(pg_result($resaco,$conresaco,'j118_pontosini'))."','$this->j118_pontosini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j118_pontosfim"]) || $this->j118_pontosfim != "")
           $resac = db_query("insert into db_acount values($acount,2673,15179,'".AddSlashes(pg_result($resaco,$conresaco,'j118_pontosfim'))."','$this->j118_pontosfim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j118_fatorreajuste"]) || $this->j118_fatorreajuste != "")
           $resac = db_query("insert into db_acount values($acount,2673,15180,'".AddSlashes(pg_result($resaco,$conresaco,'j118_fatorreajuste'))."','$this->j118_fatorreajuste',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j118_anousu"]) || $this->j118_anousu != "")
           $resac = db_query("insert into db_acount values($acount,2673,15181,'".AddSlashes(pg_result($resaco,$conresaco,'j118_anousu'))."','$this->j118_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Iptu Padrão Construção Ponto nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j118_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Iptu Padrão Construção Ponto nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j118_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j118_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j118_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j118_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15175,'$j118_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2673,15175,'','".AddSlashes(pg_result($resaco,$iresaco,'j118_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2673,15176,'','".AddSlashes(pg_result($resaco,$iresaco,'j118_iptupadraoconstr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2673,15177,'','".AddSlashes(pg_result($resaco,$iresaco,'j118_caracter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2673,15178,'','".AddSlashes(pg_result($resaco,$iresaco,'j118_pontosini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2673,15179,'','".AddSlashes(pg_result($resaco,$iresaco,'j118_pontosfim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2673,15180,'','".AddSlashes(pg_result($resaco,$iresaco,'j118_fatorreajuste'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2673,15181,'','".AddSlashes(pg_result($resaco,$iresaco,'j118_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptupadraoconstrpontos
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j118_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j118_sequencial = $j118_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Iptu Padrão Construção Ponto nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j118_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Iptu Padrão Construção Ponto nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j118_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j118_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptupadraoconstrpontos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j118_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptupadraoconstrpontos ";
     $sql .= "      inner join caracter  on  caracter.j31_codigo = iptupadraoconstrpontos.j118_caracter";
     $sql .= "      inner join iptupadraoconstr  on  iptupadraoconstr.j115_sequencial = iptupadraoconstrpontos.j118_iptupadraoconstr";
     $sql .= "      inner join cargrup  on  cargrup.j32_grupo = caracter.j31_grupo";
     $sql2 = "";
     if($dbwhere==""){
       if($j118_sequencial!=null ){
         $sql2 .= " where iptupadraoconstrpontos.j118_sequencial = $j118_sequencial "; 
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
   function sql_query_file ( $j118_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptupadraoconstrpontos ";
     $sql2 = "";
     if($dbwhere==""){
       if($j118_sequencial!=null ){
         $sql2 .= " where iptupadraoconstrpontos.j118_sequencial = $j118_sequencial "; 
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