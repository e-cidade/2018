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

//MODULO: cadastro
//CLASSE DA ENTIDADE iptucadtaxaexe
class cl_iptucadtaxaexe { 
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
   var $j08_iptucadtaxaexe = 0; 
   var $j08_iptucadtaxa = 0; 
   var $j08_tabrec = 0; 
   var $j08_valor = 0; 
   var $j08_aliq = 0; 
   var $j08_anousu = 0; 
   var $j08_iptucalh = 0; 
   var $j08_db_sysfuncoes = 0; 
   var $j08_histisen = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j08_iptucadtaxaexe = int4 = Codigo taxa/exerc 
                 j08_iptucadtaxa = int4 = Codigo da taxa 
                 j08_tabrec = int4 = Receita 
                 j08_valor = float8 = Valor 
                 j08_aliq = float8 = Aliquota 
                 j08_anousu = int4 = Exercício 
                 j08_iptucalh = int4 = Historico 
                 j08_db_sysfuncoes = int4 = Código Função 
                 j08_histisen = int8 = Código do histórico 
                 ";
   //funcao construtor da classe 
   function cl_iptucadtaxaexe() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("iptucadtaxaexe"); 
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
       $this->j08_iptucadtaxaexe = ($this->j08_iptucadtaxaexe == ""?@$GLOBALS["HTTP_POST_VARS"]["j08_iptucadtaxaexe"]:$this->j08_iptucadtaxaexe);
       $this->j08_iptucadtaxa = ($this->j08_iptucadtaxa == ""?@$GLOBALS["HTTP_POST_VARS"]["j08_iptucadtaxa"]:$this->j08_iptucadtaxa);
       $this->j08_tabrec = ($this->j08_tabrec == ""?@$GLOBALS["HTTP_POST_VARS"]["j08_tabrec"]:$this->j08_tabrec);
       $this->j08_valor = ($this->j08_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["j08_valor"]:$this->j08_valor);
       $this->j08_aliq = ($this->j08_aliq == ""?@$GLOBALS["HTTP_POST_VARS"]["j08_aliq"]:$this->j08_aliq);
       $this->j08_anousu = ($this->j08_anousu == ""?@$GLOBALS["HTTP_POST_VARS"]["j08_anousu"]:$this->j08_anousu);
       $this->j08_iptucalh = ($this->j08_iptucalh == ""?@$GLOBALS["HTTP_POST_VARS"]["j08_iptucalh"]:$this->j08_iptucalh);
       $this->j08_db_sysfuncoes = ($this->j08_db_sysfuncoes == ""?@$GLOBALS["HTTP_POST_VARS"]["j08_db_sysfuncoes"]:$this->j08_db_sysfuncoes);
       $this->j08_histisen = ($this->j08_histisen == ""?@$GLOBALS["HTTP_POST_VARS"]["j08_histisen"]:$this->j08_histisen);
     }else{
       $this->j08_iptucadtaxaexe = ($this->j08_iptucadtaxaexe == ""?@$GLOBALS["HTTP_POST_VARS"]["j08_iptucadtaxaexe"]:$this->j08_iptucadtaxaexe);
       $this->j08_iptucadtaxa = ($this->j08_iptucadtaxa == ""?@$GLOBALS["HTTP_POST_VARS"]["j08_iptucadtaxa"]:$this->j08_iptucadtaxa);
     }
   }
   // funcao para inclusao
   function incluir ($j08_iptucadtaxaexe){ 
      $this->atualizacampos();
     if($this->j08_tabrec == null ){ 
       $this->erro_sql = " Campo Receita nao Informado.";
       $this->erro_campo = "j08_tabrec";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j08_valor == null ){ 
       $this->erro_sql = " Campo Valor nao Informado.";
       $this->erro_campo = "j08_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j08_aliq == null ){ 
       $this->erro_sql = " Campo Aliquota nao Informado.";
       $this->erro_campo = "j08_aliq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j08_anousu == null ){ 
       $this->erro_sql = " Campo Exercício nao Informado.";
       $this->erro_campo = "j08_anousu";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j08_iptucalh == null ){ 
       $this->erro_sql = " Campo Historico nao Informado.";
       $this->erro_campo = "j08_iptucalh";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j08_db_sysfuncoes == null ){ 
       $this->erro_sql = " Campo Código Função nao Informado.";
       $this->erro_campo = "j08_db_sysfuncoes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j08_histisen == null ){ 
       $this->erro_sql = " Campo Código do histórico nao Informado.";
       $this->erro_campo = "j08_histisen";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j08_iptucadtaxaexe == "" || $j08_iptucadtaxaexe == null ){
       $result = db_query("select nextval('iptucadtaxaexe_j08_iptucadtaxaexe_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: iptucadtaxaexe_j08_iptucadtaxaexe_seq do campo: j08_iptucadtaxaexe"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j08_iptucadtaxaexe = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from iptucadtaxaexe_j08_iptucadtaxaexe_seq");
       if(($result != false) && (pg_result($result,0,0) < $j08_iptucadtaxaexe)){
         $this->erro_sql = " Campo j08_iptucadtaxaexe maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j08_iptucadtaxaexe = $j08_iptucadtaxaexe; 
       }
     }
     if(($this->j08_iptucadtaxaexe == null) || ($this->j08_iptucadtaxaexe == "") ){ 
       $this->erro_sql = " Campo j08_iptucadtaxaexe nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into iptucadtaxaexe(
                                       j08_iptucadtaxaexe 
                                      ,j08_iptucadtaxa 
                                      ,j08_tabrec 
                                      ,j08_valor 
                                      ,j08_aliq 
                                      ,j08_anousu 
                                      ,j08_iptucalh 
                                      ,j08_db_sysfuncoes 
                                      ,j08_histisen 
                       )
                values (
                                $this->j08_iptucadtaxaexe 
                               ,$this->j08_iptucadtaxa 
                               ,$this->j08_tabrec 
                               ,$this->j08_valor 
                               ,$this->j08_aliq 
                               ,$this->j08_anousu 
                               ,$this->j08_iptucalh 
                               ,$this->j08_db_sysfuncoes 
                               ,$this->j08_histisen 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de dados da taxa por ano ($this->j08_iptucadtaxaexe) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de dados da taxa por ano já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de dados da taxa por ano ($this->j08_iptucadtaxaexe) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j08_iptucadtaxaexe;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j08_iptucadtaxaexe));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9489,'$this->j08_iptucadtaxaexe','I')");
       $resac = db_query("insert into db_acount values($acount,1629,9489,'','".AddSlashes(pg_result($resaco,0,'j08_iptucadtaxaexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1629,9490,'','".AddSlashes(pg_result($resaco,0,'j08_iptucadtaxa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1629,9492,'','".AddSlashes(pg_result($resaco,0,'j08_tabrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1629,9493,'','".AddSlashes(pg_result($resaco,0,'j08_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1629,9517,'','".AddSlashes(pg_result($resaco,0,'j08_aliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1629,9491,'','".AddSlashes(pg_result($resaco,0,'j08_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1629,9519,'','".AddSlashes(pg_result($resaco,0,'j08_iptucalh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1629,9520,'','".AddSlashes(pg_result($resaco,0,'j08_db_sysfuncoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1629,9859,'','".AddSlashes(pg_result($resaco,0,'j08_histisen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j08_iptucadtaxaexe=null) { 
      $this->atualizacampos();
     $sql = " update iptucadtaxaexe set ";
     $virgula = "";
     if(trim($this->j08_iptucadtaxaexe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j08_iptucadtaxaexe"])){ 
       $sql  .= $virgula." j08_iptucadtaxaexe = $this->j08_iptucadtaxaexe ";
       $virgula = ",";
       if(trim($this->j08_iptucadtaxaexe) == null ){ 
         $this->erro_sql = " Campo Codigo taxa/exerc nao Informado.";
         $this->erro_campo = "j08_iptucadtaxaexe";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j08_iptucadtaxa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j08_iptucadtaxa"])){ 
       $sql  .= $virgula." j08_iptucadtaxa = $this->j08_iptucadtaxa ";
       $virgula = ",";
       if(trim($this->j08_iptucadtaxa) == null ){ 
         $this->erro_sql = " Campo Codigo da taxa nao Informado.";
         $this->erro_campo = "j08_iptucadtaxa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j08_tabrec)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j08_tabrec"])){ 
       $sql  .= $virgula." j08_tabrec = $this->j08_tabrec ";
       $virgula = ",";
       if(trim($this->j08_tabrec) == null ){ 
         $this->erro_sql = " Campo Receita nao Informado.";
         $this->erro_campo = "j08_tabrec";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j08_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j08_valor"])){ 
       $sql  .= $virgula." j08_valor = $this->j08_valor ";
       $virgula = ",";
       if(trim($this->j08_valor) == null ){ 
         $this->erro_sql = " Campo Valor nao Informado.";
         $this->erro_campo = "j08_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j08_aliq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j08_aliq"])){ 
       $sql  .= $virgula." j08_aliq = $this->j08_aliq ";
       $virgula = ",";
       if(trim($this->j08_aliq) == null ){ 
         $this->erro_sql = " Campo Aliquota nao Informado.";
         $this->erro_campo = "j08_aliq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j08_anousu)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j08_anousu"])){ 
       $sql  .= $virgula." j08_anousu = $this->j08_anousu ";
       $virgula = ",";
       if(trim($this->j08_anousu) == null ){ 
         $this->erro_sql = " Campo Exercício nao Informado.";
         $this->erro_campo = "j08_anousu";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j08_iptucalh)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j08_iptucalh"])){ 
       $sql  .= $virgula." j08_iptucalh = $this->j08_iptucalh ";
       $virgula = ",";
       if(trim($this->j08_iptucalh) == null ){ 
         $this->erro_sql = " Campo Historico nao Informado.";
         $this->erro_campo = "j08_iptucalh";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j08_db_sysfuncoes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j08_db_sysfuncoes"])){ 
       $sql  .= $virgula." j08_db_sysfuncoes = $this->j08_db_sysfuncoes ";
       $virgula = ",";
       if(trim($this->j08_db_sysfuncoes) == null ){ 
         $this->erro_sql = " Campo Código Função nao Informado.";
         $this->erro_campo = "j08_db_sysfuncoes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j08_histisen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j08_histisen"])){ 
       $sql  .= $virgula." j08_histisen = $this->j08_histisen ";
       $virgula = ",";
       if(trim($this->j08_histisen) == null ){ 
         $this->erro_sql = " Campo Código do histórico nao Informado.";
         $this->erro_campo = "j08_histisen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j08_iptucadtaxaexe!=null){
       $sql .= " j08_iptucadtaxaexe = $this->j08_iptucadtaxaexe";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j08_iptucadtaxaexe));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9489,'$this->j08_iptucadtaxaexe','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j08_iptucadtaxaexe"]))
           $resac = db_query("insert into db_acount values($acount,1629,9489,'".AddSlashes(pg_result($resaco,$conresaco,'j08_iptucadtaxaexe'))."','$this->j08_iptucadtaxaexe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j08_iptucadtaxa"]))
           $resac = db_query("insert into db_acount values($acount,1629,9490,'".AddSlashes(pg_result($resaco,$conresaco,'j08_iptucadtaxa'))."','$this->j08_iptucadtaxa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j08_tabrec"]))
           $resac = db_query("insert into db_acount values($acount,1629,9492,'".AddSlashes(pg_result($resaco,$conresaco,'j08_tabrec'))."','$this->j08_tabrec',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j08_valor"]))
           $resac = db_query("insert into db_acount values($acount,1629,9493,'".AddSlashes(pg_result($resaco,$conresaco,'j08_valor'))."','$this->j08_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j08_aliq"]))
           $resac = db_query("insert into db_acount values($acount,1629,9517,'".AddSlashes(pg_result($resaco,$conresaco,'j08_aliq'))."','$this->j08_aliq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j08_anousu"]))
           $resac = db_query("insert into db_acount values($acount,1629,9491,'".AddSlashes(pg_result($resaco,$conresaco,'j08_anousu'))."','$this->j08_anousu',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j08_iptucalh"]))
           $resac = db_query("insert into db_acount values($acount,1629,9519,'".AddSlashes(pg_result($resaco,$conresaco,'j08_iptucalh'))."','$this->j08_iptucalh',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j08_db_sysfuncoes"]))
           $resac = db_query("insert into db_acount values($acount,1629,9520,'".AddSlashes(pg_result($resaco,$conresaco,'j08_db_sysfuncoes'))."','$this->j08_db_sysfuncoes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j08_histisen"]))
           $resac = db_query("insert into db_acount values($acount,1629,9859,'".AddSlashes(pg_result($resaco,$conresaco,'j08_histisen'))."','$this->j08_histisen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de dados da taxa por ano nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j08_iptucadtaxaexe;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de dados da taxa por ano nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j08_iptucadtaxaexe;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j08_iptucadtaxaexe;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j08_iptucadtaxaexe=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j08_iptucadtaxaexe));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9489,'$j08_iptucadtaxaexe','E')");
         $resac = db_query("insert into db_acount values($acount,1629,9489,'','".AddSlashes(pg_result($resaco,$iresaco,'j08_iptucadtaxaexe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1629,9490,'','".AddSlashes(pg_result($resaco,$iresaco,'j08_iptucadtaxa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1629,9492,'','".AddSlashes(pg_result($resaco,$iresaco,'j08_tabrec'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1629,9493,'','".AddSlashes(pg_result($resaco,$iresaco,'j08_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1629,9517,'','".AddSlashes(pg_result($resaco,$iresaco,'j08_aliq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1629,9491,'','".AddSlashes(pg_result($resaco,$iresaco,'j08_anousu'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1629,9519,'','".AddSlashes(pg_result($resaco,$iresaco,'j08_iptucalh'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1629,9520,'','".AddSlashes(pg_result($resaco,$iresaco,'j08_db_sysfuncoes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1629,9859,'','".AddSlashes(pg_result($resaco,$iresaco,'j08_histisen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from iptucadtaxaexe
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j08_iptucadtaxaexe != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j08_iptucadtaxaexe = $j08_iptucadtaxaexe ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de dados da taxa por ano nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j08_iptucadtaxaexe;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de dados da taxa por ano nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j08_iptucadtaxaexe;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j08_iptucadtaxaexe;
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
        $this->erro_sql   = "Record Vazio na Tabela:iptucadtaxaexe";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j08_iptucadtaxaexe=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptucadtaxaexe ";
     $sql .= "      inner join tabrec  on  tabrec.k02_codigo = iptucadtaxaexe.j08_tabrec";
     $sql .= "      inner join db_sysfuncoes  on  db_sysfuncoes.codfuncao = iptucadtaxaexe.j08_db_sysfuncoes";
     $sql .= "      inner join iptucalh       on  iptucalh.j17_codhis = iptucadtaxaexe.j08_iptucalh ";
//		 $sql .= "                               and  iptucalh.j17_codhis = iptucadtaxaexe.j08_histisen";
     $sql .= "      inner join iptucadtaxa  on  iptucadtaxa.j07_iptucadtaxa = iptucadtaxaexe.j08_iptucadtaxa";
     $sql .= "      inner join tabrecjm  on  tabrecjm.k02_codjm = tabrec.k02_codjm";
     $sql2 = "";
     if($dbwhere==""){
       if($j08_iptucadtaxaexe!=null ){
         $sql2 .= " where iptucadtaxaexe.j08_iptucadtaxaexe = $j08_iptucadtaxaexe "; 
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
   function sql_query_file ( $j08_iptucadtaxaexe=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from iptucadtaxaexe ";
     $sql2 = "";
     if($dbwhere==""){
       if($j08_iptucadtaxaexe!=null ){
         $sql2 .= " where iptucadtaxaexe.j08_iptucadtaxaexe = $j08_iptucadtaxaexe "; 
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