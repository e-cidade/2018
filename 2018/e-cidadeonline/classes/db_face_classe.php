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

//MODULO: cadastro
//CLASSE DA ENTIDADE face
class cl_face { 
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
   var $j37_face = 0; 
   var $j37_setor = null; 
   var $j37_quadra = null; 
   var $j37_codigo = 0; 
   var $j37_lado = null; 
   var $j37_valor = 0; 
   var $j37_exten = 0; 
   var $j37_profr = 0; 
   var $j37_outros = null; 
   var $j37_vlcons = 0; 
   var $j37_zona = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j37_face = int4 = Cód. Face 
                 j37_setor = char(4) = Setor 
                 j37_quadra = char(4) = Quadra 
                 j37_codigo = int4 = Logradouro 
                 j37_lado = char(1) = Lado 
                 j37_valor = float8 = Valor M2 terreno 
                 j37_exten = float8 = Extensão 
                 j37_profr = float8 = Profundidade Quadra 
                 j37_outros = varchar(40) = Outros Dados 
                 j37_vlcons = float8 = Valor M2 Construção 
                 j37_zona = int4 = Zona 
                 ";
   //funcao construtor da classe 
   function cl_face() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("face"); 
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
       $this->j37_face = ($this->j37_face == ""?@$GLOBALS["HTTP_POST_VARS"]["j37_face"]:$this->j37_face);
       $this->j37_setor = ($this->j37_setor == ""?@$GLOBALS["HTTP_POST_VARS"]["j37_setor"]:$this->j37_setor);
       $this->j37_quadra = ($this->j37_quadra == ""?@$GLOBALS["HTTP_POST_VARS"]["j37_quadra"]:$this->j37_quadra);
       $this->j37_codigo = ($this->j37_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["j37_codigo"]:$this->j37_codigo);
       $this->j37_lado = ($this->j37_lado == ""?@$GLOBALS["HTTP_POST_VARS"]["j37_lado"]:$this->j37_lado);
       $this->j37_valor = ($this->j37_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["j37_valor"]:$this->j37_valor);
       $this->j37_exten = ($this->j37_exten == ""?@$GLOBALS["HTTP_POST_VARS"]["j37_exten"]:$this->j37_exten);
       $this->j37_profr = ($this->j37_profr == ""?@$GLOBALS["HTTP_POST_VARS"]["j37_profr"]:$this->j37_profr);
       $this->j37_outros = ($this->j37_outros == ""?@$GLOBALS["HTTP_POST_VARS"]["j37_outros"]:$this->j37_outros);
       $this->j37_vlcons = ($this->j37_vlcons == ""?@$GLOBALS["HTTP_POST_VARS"]["j37_vlcons"]:$this->j37_vlcons);
       $this->j37_zona = ($this->j37_zona == ""?@$GLOBALS["HTTP_POST_VARS"]["j37_zona"]:$this->j37_zona);
     }else{
       $this->j37_face = ($this->j37_face == ""?@$GLOBALS["HTTP_POST_VARS"]["j37_face"]:$this->j37_face);
     }
   }
   // funcao para inclusao
   function incluir ($j37_face){ 
      $this->atualizacampos();
     if($this->j37_setor == null ){ 
       $this->erro_sql = " Campo Setor nao Informado.";
       $this->erro_campo = "j37_setor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j37_quadra == null ){ 
       $this->erro_sql = " Campo Quadra nao Informado.";
       $this->erro_campo = "j37_quadra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j37_codigo == null ){ 
       $this->erro_sql = " Campo Logradouro nao Informado.";
       $this->erro_campo = "j37_codigo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j37_lado == null ){ 
       $this->erro_sql = " Campo Lado nao Informado.";
       $this->erro_campo = "j37_lado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j37_valor == null ){ 
       $this->erro_sql = " Campo Valor M2 terreno nao Informado.";
       $this->erro_campo = "j37_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j37_exten == null ){ 
       $this->j37_exten = "0";
     }
     if($this->j37_profr == null ){ 
       $this->j37_profr = "0";
     }
     if($this->j37_vlcons == null ){ 
       $this->erro_sql = " Campo Valor M2 Construção nao Informado.";
       $this->erro_campo = "j37_vlcons";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j37_zona == null ){ 
       $this->j37_zona = "0";
     }
     if($j37_face == "" || $j37_face == null ){
       $result = db_query("select nextval('face_j37_face_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: face_j37_face_seq do campo: j37_face"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j37_face = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from face_j37_face_seq");
       if(($result != false) && (pg_result($result,0,0) < $j37_face)){
         $this->erro_sql = " Campo j37_face maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j37_face = $j37_face; 
       }
     }
     if(($this->j37_face == null) || ($this->j37_face == "") ){ 
       $this->erro_sql = " Campo j37_face nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into face(
                                       j37_face 
                                      ,j37_setor 
                                      ,j37_quadra 
                                      ,j37_codigo 
                                      ,j37_lado 
                                      ,j37_valor 
                                      ,j37_exten 
                                      ,j37_profr 
                                      ,j37_outros 
                                      ,j37_vlcons 
                                      ,j37_zona 
                       )
                values (
                                $this->j37_face 
                               ,'$this->j37_setor' 
                               ,'$this->j37_quadra' 
                               ,$this->j37_codigo 
                               ,'$this->j37_lado' 
                               ,$this->j37_valor 
                               ,$this->j37_exten 
                               ,$this->j37_profr 
                               ,'$this->j37_outros' 
                               ,$this->j37_vlcons 
                               ,$this->j37_zona 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Face de Quadra ($this->j37_face) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Face de Quadra já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Face de Quadra ($this->j37_face) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j37_face;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j37_face));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,62,'$this->j37_face','I')");
       $resac = db_query("insert into db_acount values($acount,15,62,'','".AddSlashes(pg_result($resaco,0,'j37_face'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,15,63,'','".AddSlashes(pg_result($resaco,0,'j37_setor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,15,64,'','".AddSlashes(pg_result($resaco,0,'j37_quadra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,15,65,'','".AddSlashes(pg_result($resaco,0,'j37_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,15,66,'','".AddSlashes(pg_result($resaco,0,'j37_lado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,15,67,'','".AddSlashes(pg_result($resaco,0,'j37_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,15,68,'','".AddSlashes(pg_result($resaco,0,'j37_exten'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,15,69,'','".AddSlashes(pg_result($resaco,0,'j37_profr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,15,70,'','".AddSlashes(pg_result($resaco,0,'j37_outros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,15,807,'','".AddSlashes(pg_result($resaco,0,'j37_vlcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,15,5086,'','".AddSlashes(pg_result($resaco,0,'j37_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j37_face=null) { 
      $this->atualizacampos();
     $sql = " update face set ";
     $virgula = "";
     if(trim($this->j37_face)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j37_face"])){ 
       $sql  .= $virgula." j37_face = $this->j37_face ";
       $virgula = ",";
       if(trim($this->j37_face) == null ){ 
         $this->erro_sql = " Campo Cód. Face nao Informado.";
         $this->erro_campo = "j37_face";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j37_setor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j37_setor"])){ 
       $sql  .= $virgula." j37_setor = '$this->j37_setor' ";
       $virgula = ",";
       if(trim($this->j37_setor) == null ){ 
         $this->erro_sql = " Campo Setor nao Informado.";
         $this->erro_campo = "j37_setor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j37_quadra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j37_quadra"])){ 
       $sql  .= $virgula." j37_quadra = '$this->j37_quadra' ";
       $virgula = ",";
       if(trim($this->j37_quadra) == null ){ 
         $this->erro_sql = " Campo Quadra nao Informado.";
         $this->erro_campo = "j37_quadra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j37_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j37_codigo"])){ 
       $sql  .= $virgula." j37_codigo = $this->j37_codigo ";
       $virgula = ",";
       if(trim($this->j37_codigo) == null ){ 
         $this->erro_sql = " Campo Logradouro nao Informado.";
         $this->erro_campo = "j37_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j37_lado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j37_lado"])){ 
       $sql  .= $virgula." j37_lado = '$this->j37_lado' ";
       $virgula = ",";
       if(trim($this->j37_lado) == null ){ 
         $this->erro_sql = " Campo Lado nao Informado.";
         $this->erro_campo = "j37_lado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j37_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j37_valor"])){ 
       $sql  .= $virgula." j37_valor = $this->j37_valor ";
       $virgula = ",";
       if(trim($this->j37_valor) == null ){ 
         $this->erro_sql = " Campo Valor M2 terreno nao Informado.";
         $this->erro_campo = "j37_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j37_exten)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j37_exten"])){ 
        if(trim($this->j37_exten)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j37_exten"])){ 
           $this->j37_exten = "0" ; 
        } 
       $sql  .= $virgula." j37_exten = $this->j37_exten ";
       $virgula = ",";
     }
     if(trim($this->j37_profr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j37_profr"])){ 
        if(trim($this->j37_profr)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j37_profr"])){ 
           $this->j37_profr = "0" ; 
        } 
       $sql  .= $virgula." j37_profr = $this->j37_profr ";
       $virgula = ",";
     }
     if(trim($this->j37_outros)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j37_outros"])){ 
       $sql  .= $virgula." j37_outros = '$this->j37_outros' ";
       $virgula = ",";
     }
     if(trim($this->j37_vlcons)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j37_vlcons"])){ 
       $sql  .= $virgula." j37_vlcons = $this->j37_vlcons ";
       $virgula = ",";
       if(trim($this->j37_vlcons) == null ){ 
         $this->erro_sql = " Campo Valor M2 Construção nao Informado.";
         $this->erro_campo = "j37_vlcons";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j37_zona)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j37_zona"])){ 
        if(trim($this->j37_zona)=="" && isset($GLOBALS["HTTP_POST_VARS"]["j37_zona"])){ 
           $this->j37_zona = "0" ; 
        } 
       $sql  .= $virgula." j37_zona = $this->j37_zona ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($j37_face!=null){
       $sql .= " j37_face = $this->j37_face";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j37_face));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,62,'$this->j37_face','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j37_face"]))
           $resac = db_query("insert into db_acount values($acount,15,62,'".AddSlashes(pg_result($resaco,$conresaco,'j37_face'))."','$this->j37_face',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j37_setor"]))
           $resac = db_query("insert into db_acount values($acount,15,63,'".AddSlashes(pg_result($resaco,$conresaco,'j37_setor'))."','$this->j37_setor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j37_quadra"]))
           $resac = db_query("insert into db_acount values($acount,15,64,'".AddSlashes(pg_result($resaco,$conresaco,'j37_quadra'))."','$this->j37_quadra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j37_codigo"]))
           $resac = db_query("insert into db_acount values($acount,15,65,'".AddSlashes(pg_result($resaco,$conresaco,'j37_codigo'))."','$this->j37_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j37_lado"]))
           $resac = db_query("insert into db_acount values($acount,15,66,'".AddSlashes(pg_result($resaco,$conresaco,'j37_lado'))."','$this->j37_lado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j37_valor"]))
           $resac = db_query("insert into db_acount values($acount,15,67,'".AddSlashes(pg_result($resaco,$conresaco,'j37_valor'))."','$this->j37_valor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j37_exten"]))
           $resac = db_query("insert into db_acount values($acount,15,68,'".AddSlashes(pg_result($resaco,$conresaco,'j37_exten'))."','$this->j37_exten',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j37_profr"]))
           $resac = db_query("insert into db_acount values($acount,15,69,'".AddSlashes(pg_result($resaco,$conresaco,'j37_profr'))."','$this->j37_profr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j37_outros"]))
           $resac = db_query("insert into db_acount values($acount,15,70,'".AddSlashes(pg_result($resaco,$conresaco,'j37_outros'))."','$this->j37_outros',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j37_vlcons"]))
           $resac = db_query("insert into db_acount values($acount,15,807,'".AddSlashes(pg_result($resaco,$conresaco,'j37_vlcons'))."','$this->j37_vlcons',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j37_zona"]))
           $resac = db_query("insert into db_acount values($acount,15,5086,'".AddSlashes(pg_result($resaco,$conresaco,'j37_zona'))."','$this->j37_zona',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Face de Quadra nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j37_face;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Face de Quadra nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j37_face;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j37_face;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j37_face=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j37_face));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,62,'$j37_face','E')");
         $resac = db_query("insert into db_acount values($acount,15,62,'','".AddSlashes(pg_result($resaco,$iresaco,'j37_face'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,15,63,'','".AddSlashes(pg_result($resaco,$iresaco,'j37_setor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,15,64,'','".AddSlashes(pg_result($resaco,$iresaco,'j37_quadra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,15,65,'','".AddSlashes(pg_result($resaco,$iresaco,'j37_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,15,66,'','".AddSlashes(pg_result($resaco,$iresaco,'j37_lado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,15,67,'','".AddSlashes(pg_result($resaco,$iresaco,'j37_valor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,15,68,'','".AddSlashes(pg_result($resaco,$iresaco,'j37_exten'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,15,69,'','".AddSlashes(pg_result($resaco,$iresaco,'j37_profr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,15,70,'','".AddSlashes(pg_result($resaco,$iresaco,'j37_outros'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,15,807,'','".AddSlashes(pg_result($resaco,$iresaco,'j37_vlcons'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,15,5086,'','".AddSlashes(pg_result($resaco,$iresaco,'j37_zona'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from face
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j37_face != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j37_face = $j37_face ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Face de Quadra nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j37_face;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Face de Quadra nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j37_face;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j37_face;
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
        $this->erro_sql   = "Record Vazio na Tabela:face";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $j37_face=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from face ";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = face.j37_codigo";
     $sql .= "      inner join setor  on  setor.j30_codi = face.j37_setor";
     $sql2 = "";
     if($dbwhere==""){
       if($j37_face!=null ){
         $sql2 .= " where face.j37_face = $j37_face "; 
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
   function sql_query_file ( $j37_face=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from face ";
     $sql2 = "";
     if($dbwhere==""){
       if($j37_face!=null ){
         $sql2 .= " where face.j37_face = $j37_face "; 
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