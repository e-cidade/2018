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
//CLASSE DA ENTIDADE moblevantamentolog
class cl_moblevantamentolog { 
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
   var $j98_sequen = 0; 
   var $j98_codimporta = 0; 
   var $j98_matric = 0; 
   var $j98_codigo = 0; 
   var $j98_testada = null; 
   var $j98_pavim = 0; 
   var $j98_agua = null; 
   var $j98_esgoto = null; 
   var $j98_eletrica = null; 
   var $j98_meiofio = null; 
   var $j98_iluminacao = null; 
   var $j98_telefonia = null; 
   var $j98_lixo = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j98_sequen = int4 = Sequencial 
                 j98_codimporta = int4 = Código Importação 
                 j98_matric = int4 = Matrícula 
                 j98_codigo = int4 = Logradouro 
                 j98_testada = varchar(15) = Testada 
                 j98_pavim = int4 = Pavimentação 
                 j98_agua = varchar(1) = Água 
                 j98_esgoto = varchar(1) = Esgoto 
                 j98_eletrica = varchar(1) = Elétrica 
                 j98_meiofio = varchar(1) = Meio-Fio 
                 j98_iluminacao = varchar(1) = Iluminação 
                 j98_telefonia = varchar(1) = Telefonia 
                 j98_lixo = varchar(1) = Coleta Lixo 
                 ";
   //funcao construtor da classe 
   function cl_moblevantamentolog() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("moblevantamentolog"); 
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
       $this->j98_sequen = ($this->j98_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["j98_sequen"]:$this->j98_sequen);
       $this->j98_codimporta = ($this->j98_codimporta == ""?@$GLOBALS["HTTP_POST_VARS"]["j98_codimporta"]:$this->j98_codimporta);
       $this->j98_matric = ($this->j98_matric == ""?@$GLOBALS["HTTP_POST_VARS"]["j98_matric"]:$this->j98_matric);
       $this->j98_codigo = ($this->j98_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j98_codigo"]:$this->j98_codigo);
       $this->j98_testada = ($this->j98_testada == ""?@$GLOBALS["HTTP_POST_VARS"]["j98_testada"]:$this->j98_testada);
       $this->j98_pavim = ($this->j98_pavim == ""?@$GLOBALS["HTTP_POST_VARS"]["j98_pavim"]:$this->j98_pavim);
       $this->j98_agua = ($this->j98_agua == ""?@$GLOBALS["HTTP_POST_VARS"]["j98_agua"]:$this->j98_agua);
       $this->j98_esgoto = ($this->j98_esgoto == ""?@$GLOBALS["HTTP_POST_VARS"]["j98_esgoto"]:$this->j98_esgoto);
       $this->j98_eletrica = ($this->j98_eletrica == ""?@$GLOBALS["HTTP_POST_VARS"]["j98_eletrica"]:$this->j98_eletrica);
       $this->j98_meiofio = ($this->j98_meiofio == ""?@$GLOBALS["HTTP_POST_VARS"]["j98_meiofio"]:$this->j98_meiofio);
       $this->j98_iluminacao = ($this->j98_iluminacao == ""?@$GLOBALS["HTTP_POST_VARS"]["j98_iluminacao"]:$this->j98_iluminacao);
       $this->j98_telefonia = ($this->j98_telefonia == ""?@$GLOBALS["HTTP_POST_VARS"]["j98_telefonia"]:$this->j98_telefonia);
       $this->j98_lixo = ($this->j98_lixo == ""?@$GLOBALS["HTTP_POST_VARS"]["j98_lixo"]:$this->j98_lixo);
     }else{
       $this->j98_sequen = ($this->j98_sequen == ""?@$GLOBALS["HTTP_POST_VARS"]["j98_sequen"]:$this->j98_sequen);
     }
   }
   // funcao para inclusao
   function incluir ($j98_sequen){ 
      $this->atualizacampos();
     if($this->j98_codimporta == null ){ 
       $this->erro_sql = " Campo Código Importação nao Informado.";
       $this->erro_campo = "j98_codimporta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j98_matric == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "j98_matric";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j98_codigo == null ){ 
       $this->erro_sql = " Campo Logradouro nao Informado.";
       $this->erro_campo = "j98_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j98_testada == null ){ 
       $this->erro_sql = " Campo Testada nao Informado.";
       $this->erro_campo = "j98_testada";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j98_pavim == null ){ 
       $this->erro_sql = " Campo Pavimentação nao Informado.";
       $this->erro_campo = "j98_pavim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j98_agua == null ){ 
       $this->erro_sql = " Campo Água nao Informado.";
       $this->erro_campo = "j98_agua";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j98_esgoto == null ){ 
       $this->erro_sql = " Campo Esgoto nao Informado.";
       $this->erro_campo = "j98_esgoto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j98_eletrica == null ){ 
       $this->erro_sql = " Campo Elétrica nao Informado.";
       $this->erro_campo = "j98_eletrica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j98_meiofio == null ){ 
       $this->erro_sql = " Campo Meio-Fio nao Informado.";
       $this->erro_campo = "j98_meiofio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j98_iluminacao == null ){ 
       $this->erro_sql = " Campo Iluminação nao Informado.";
       $this->erro_campo = "j98_iluminacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j98_telefonia == null ){ 
       $this->erro_sql = " Campo Telefonia nao Informado.";
       $this->erro_campo = "j98_telefonia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j98_lixo == null ){ 
       $this->erro_sql = " Campo Coleta Lixo nao Informado.";
       $this->erro_campo = "j98_lixo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j98_sequen == "" || $j98_sequen == null ){
       $result = db_query("select nextval('moblevantamentolog_j98_sequen_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: moblevantamentolog_j98_sequen_seq do campo: j98_sequen"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j98_sequen = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from moblevantamentolog_j98_sequen_seq");
       if(($result != false) && (pg_result($result,0,0) < $j98_sequen)){
         $this->erro_sql = " Campo j98_sequen maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j98_sequen = $j98_sequen; 
       }
     }
     if(($this->j98_sequen == null) || ($this->j98_sequen == "") ){ 
       $this->erro_sql = " Campo j98_sequen nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into moblevantamentolog(
                                       j98_sequen 
                                      ,j98_codimporta 
                                      ,j98_matric 
                                      ,j98_codigo 
                                      ,j98_testada 
                                      ,j98_pavim 
                                      ,j98_agua 
                                      ,j98_esgoto 
                                      ,j98_eletrica 
                                      ,j98_meiofio 
                                      ,j98_iluminacao 
                                      ,j98_telefonia 
                                      ,j98_lixo 
                       )
                values (
                                $this->j98_sequen 
                               ,$this->j98_codimporta 
                               ,$this->j98_matric 
                               ,$this->j98_codigo 
                               ,'$this->j98_testada' 
                               ,$this->j98_pavim 
                               ,'$this->j98_agua' 
                               ,'$this->j98_esgoto' 
                               ,'$this->j98_eletrica' 
                               ,'$this->j98_meiofio' 
                               ,'$this->j98_iluminacao' 
                               ,'$this->j98_telefonia' 
                               ,'$this->j98_lixo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Logradouros da Matricula ($this->j98_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Logradouros da Matricula já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Logradouros da Matricula ($this->j98_sequen) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j98_sequen;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j98_sequen));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,9736,'$this->j98_sequen','I')");
       $resac = db_query("insert into db_acount values($acount,1669,9736,'','".AddSlashes(pg_result($resaco,0,'j98_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1669,9734,'','".AddSlashes(pg_result($resaco,0,'j98_codimporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1669,9699,'','".AddSlashes(pg_result($resaco,0,'j98_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1669,9700,'','".AddSlashes(pg_result($resaco,0,'j98_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1669,9701,'','".AddSlashes(pg_result($resaco,0,'j98_testada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1669,9702,'','".AddSlashes(pg_result($resaco,0,'j98_pavim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1669,9703,'','".AddSlashes(pg_result($resaco,0,'j98_agua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1669,9704,'','".AddSlashes(pg_result($resaco,0,'j98_esgoto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1669,9705,'','".AddSlashes(pg_result($resaco,0,'j98_eletrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1669,9706,'','".AddSlashes(pg_result($resaco,0,'j98_meiofio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1669,9707,'','".AddSlashes(pg_result($resaco,0,'j98_iluminacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1669,9708,'','".AddSlashes(pg_result($resaco,0,'j98_telefonia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1669,9709,'','".AddSlashes(pg_result($resaco,0,'j98_lixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j98_sequen=null) { 
      $this->atualizacampos();
     $sql = " update moblevantamentolog set ";
     $virgula = "";
     if(trim($this->j98_sequen)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j98_sequen"])){ 
       $sql  .= $virgula." j98_sequen = $this->j98_sequen ";
       $virgula = ",";
       if(trim($this->j98_sequen) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "j98_sequen";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j98_codimporta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j98_codimporta"])){ 
       $sql  .= $virgula." j98_codimporta = $this->j98_codimporta ";
       $virgula = ",";
       if(trim($this->j98_codimporta) == null ){ 
         $this->erro_sql = " Campo Código Importação nao Informado.";
         $this->erro_campo = "j98_codimporta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j98_matric)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j98_matric"])){ 
       $sql  .= $virgula." j98_matric = $this->j98_matric ";
       $virgula = ",";
       if(trim($this->j98_matric) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "j98_matric";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j98_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j98_codigo"])){ 
       $sql  .= $virgula." j98_codigo = $this->j98_codigo ";
       $virgula = ",";
       if(trim($this->j98_codigo) == null ){ 
         $this->erro_sql = " Campo Logradouro nao Informado.";
         $this->erro_campo = "j98_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j98_testada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j98_testada"])){ 
       $sql  .= $virgula." j98_testada = '$this->j98_testada' ";
       $virgula = ",";
       if(trim($this->j98_testada) == null ){ 
         $this->erro_sql = " Campo Testada nao Informado.";
         $this->erro_campo = "j98_testada";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j98_pavim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j98_pavim"])){ 
       $sql  .= $virgula." j98_pavim = $this->j98_pavim ";
       $virgula = ",";
       if(trim($this->j98_pavim) == null ){ 
         $this->erro_sql = " Campo Pavimentação nao Informado.";
         $this->erro_campo = "j98_pavim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j98_agua)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j98_agua"])){ 
       $sql  .= $virgula." j98_agua = '$this->j98_agua' ";
       $virgula = ",";
       if(trim($this->j98_agua) == null ){ 
         $this->erro_sql = " Campo Água nao Informado.";
         $this->erro_campo = "j98_agua";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j98_esgoto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j98_esgoto"])){ 
       $sql  .= $virgula." j98_esgoto = '$this->j98_esgoto' ";
       $virgula = ",";
       if(trim($this->j98_esgoto) == null ){ 
         $this->erro_sql = " Campo Esgoto nao Informado.";
         $this->erro_campo = "j98_esgoto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j98_eletrica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j98_eletrica"])){ 
       $sql  .= $virgula." j98_eletrica = '$this->j98_eletrica' ";
       $virgula = ",";
       if(trim($this->j98_eletrica) == null ){ 
         $this->erro_sql = " Campo Elétrica nao Informado.";
         $this->erro_campo = "j98_eletrica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j98_meiofio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j98_meiofio"])){ 
       $sql  .= $virgula." j98_meiofio = '$this->j98_meiofio' ";
       $virgula = ",";
       if(trim($this->j98_meiofio) == null ){ 
         $this->erro_sql = " Campo Meio-Fio nao Informado.";
         $this->erro_campo = "j98_meiofio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j98_iluminacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j98_iluminacao"])){ 
       $sql  .= $virgula." j98_iluminacao = '$this->j98_iluminacao' ";
       $virgula = ",";
       if(trim($this->j98_iluminacao) == null ){ 
         $this->erro_sql = " Campo Iluminação nao Informado.";
         $this->erro_campo = "j98_iluminacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j98_telefonia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j98_telefonia"])){ 
       $sql  .= $virgula." j98_telefonia = '$this->j98_telefonia' ";
       $virgula = ",";
       if(trim($this->j98_telefonia) == null ){ 
         $this->erro_sql = " Campo Telefonia nao Informado.";
         $this->erro_campo = "j98_telefonia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j98_lixo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j98_lixo"])){ 
       $sql  .= $virgula." j98_lixo = '$this->j98_lixo' ";
       $virgula = ",";
       if(trim($this->j98_lixo) == null ){ 
         $this->erro_sql = " Campo Coleta Lixo nao Informado.";
         $this->erro_campo = "j98_lixo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j98_sequen!=null){
       $sql .= " j98_sequen = $this->j98_sequen";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j98_sequen));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9736,'$this->j98_sequen','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j98_sequen"]))
           $resac = db_query("insert into db_acount values($acount,1669,9736,'".AddSlashes(pg_result($resaco,$conresaco,'j98_sequen'))."','$this->j98_sequen',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j98_codimporta"]))
           $resac = db_query("insert into db_acount values($acount,1669,9734,'".AddSlashes(pg_result($resaco,$conresaco,'j98_codimporta'))."','$this->j98_codimporta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j98_matric"]))
           $resac = db_query("insert into db_acount values($acount,1669,9699,'".AddSlashes(pg_result($resaco,$conresaco,'j98_matric'))."','$this->j98_matric',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j98_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1669,9700,'".AddSlashes(pg_result($resaco,$conresaco,'j98_codigo'))."','$this->j98_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j98_testada"]))
           $resac = db_query("insert into db_acount values($acount,1669,9701,'".AddSlashes(pg_result($resaco,$conresaco,'j98_testada'))."','$this->j98_testada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j98_pavim"]))
           $resac = db_query("insert into db_acount values($acount,1669,9702,'".AddSlashes(pg_result($resaco,$conresaco,'j98_pavim'))."','$this->j98_pavim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j98_agua"]))
           $resac = db_query("insert into db_acount values($acount,1669,9703,'".AddSlashes(pg_result($resaco,$conresaco,'j98_agua'))."','$this->j98_agua',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j98_esgoto"]))
           $resac = db_query("insert into db_acount values($acount,1669,9704,'".AddSlashes(pg_result($resaco,$conresaco,'j98_esgoto'))."','$this->j98_esgoto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j98_eletrica"]))
           $resac = db_query("insert into db_acount values($acount,1669,9705,'".AddSlashes(pg_result($resaco,$conresaco,'j98_eletrica'))."','$this->j98_eletrica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j98_meiofio"]))
           $resac = db_query("insert into db_acount values($acount,1669,9706,'".AddSlashes(pg_result($resaco,$conresaco,'j98_meiofio'))."','$this->j98_meiofio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j98_iluminacao"]))
           $resac = db_query("insert into db_acount values($acount,1669,9707,'".AddSlashes(pg_result($resaco,$conresaco,'j98_iluminacao'))."','$this->j98_iluminacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j98_telefonia"]))
           $resac = db_query("insert into db_acount values($acount,1669,9708,'".AddSlashes(pg_result($resaco,$conresaco,'j98_telefonia'))."','$this->j98_telefonia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j98_lixo"]))
           $resac = db_query("insert into db_acount values($acount,1669,9709,'".AddSlashes(pg_result($resaco,$conresaco,'j98_lixo'))."','$this->j98_lixo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Logradouros da Matricula nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j98_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Logradouros da Matricula nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j98_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j98_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j98_sequen=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j98_sequen));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,9736,'$j98_sequen','E')");
         $resac = db_query("insert into db_acount values($acount,1669,9736,'','".AddSlashes(pg_result($resaco,$iresaco,'j98_sequen'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1669,9734,'','".AddSlashes(pg_result($resaco,$iresaco,'j98_codimporta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1669,9699,'','".AddSlashes(pg_result($resaco,$iresaco,'j98_matric'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1669,9700,'','".AddSlashes(pg_result($resaco,$iresaco,'j98_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1669,9701,'','".AddSlashes(pg_result($resaco,$iresaco,'j98_testada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1669,9702,'','".AddSlashes(pg_result($resaco,$iresaco,'j98_pavim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1669,9703,'','".AddSlashes(pg_result($resaco,$iresaco,'j98_agua'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1669,9704,'','".AddSlashes(pg_result($resaco,$iresaco,'j98_esgoto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1669,9705,'','".AddSlashes(pg_result($resaco,$iresaco,'j98_eletrica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1669,9706,'','".AddSlashes(pg_result($resaco,$iresaco,'j98_meiofio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1669,9707,'','".AddSlashes(pg_result($resaco,$iresaco,'j98_iluminacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1669,9708,'','".AddSlashes(pg_result($resaco,$iresaco,'j98_telefonia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1669,9709,'','".AddSlashes(pg_result($resaco,$iresaco,'j98_lixo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from moblevantamentolog
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j98_sequen != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j98_sequen = $j98_sequen ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Logradouros da Matricula nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j98_sequen;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Logradouros da Matricula nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j98_sequen;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j98_sequen;
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
        $this->erro_sql   = "Record Vazio na Tabela:moblevantamentolog";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j98_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from moblevantamentolog ";
     $sql .= "      inner join mobimportacao  on  mobimportacao.j95_codimporta = moblevantamentolog.j98_codimporta";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = mobimportacao.j95_idusuario";
     $sql2 = "";
     if($dbwhere==""){
       if($j98_sequen!=null ){
         $sql2 .= " where moblevantamentolog.j98_sequen = $j98_sequen "; 
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
   function sql_query_file ( $j98_sequen=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from moblevantamentolog ";
     $sql2 = "";
     if($dbwhere==""){
       if($j98_sequen!=null ){
         $sql2 .= " where moblevantamentolog.j98_sequen = $j98_sequen "; 
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